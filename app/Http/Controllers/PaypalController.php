<?php

namespace App\Http\Controllers;


use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use App\Order;
use App\OrderItem;


class PaypalController extends Controller
{
    private $_api_context;
	public function __construct()
	{
		// setup PayPal api context
		$paypal_conf = \Config::get('paypal');
		$this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
		$this->_api_context->setConfig($paypal_conf['settings']);
    }
    //Donde se configura todo lo que se le va enviar a paypal 
    public function postPayment(){
        // Payer contiene las relaciones de datos del cliente y el pago 
        $payer = new Payer();
        $payer->setPaymentMethod('paypal'); 

        
        $items = array();
        $subtotal = 0;
        //obtener toda la informacion que vamos a utilizar 
        $cart = \Session::get('cart');
        //Currency pesos colombianos 
		$currency = 'COP';

        /*Recorrer todo el carrito y por cada producto que hay en el carrito 
        vamos a crear un objeto de la clase Item  y vamos a configurar a traves 
        de sus metodos los datos de ese producto como lo requere paypal. 

        Nombre, tipo de moneda, descripcion, numero de piezas y precio..

        */
		foreach($cart as $producto){
			$item = new Item();
			$item->setName($producto->name)
			->setCurrency($currency)
			->setDescription($producto->extract)
			->setQuantity($producto->quantity)
			->setPrice($producto->price);
			$items[] = $item;
			$subtotal += $producto->quantity * $producto->price;
        }
        

        /** Guardar en  el array  */
		$item_list = new ItemList();
        $item_list->setItems($items);
        
        /** Details nos sirve para agregar un costo para el envio  */

		$details = new Details();
		$details->setSubtotal($subtotal)
        ->setShipping(100);
        
        $total = $subtotal + 100;
        /** Objeto para guardar las cantidades, moneda total a pagar y los detalles  */
		$amount = new Amount();
		$amount->setCurrency($currency)
			->setTotal($total)
            ->setDetails($details);
            
        /* Se pasa  la cantidad que contiene el total a pagar, y el objeto que tiene los 
        items del carrito */    
		$transaction = new Transaction();
		$transaction->setAmount($amount)
			->setItemList($item_list)
            ->setDescription('Pedido de prueba en mi Laravel Tienda Online ');
            
        /** Crear objecto rediret recibe la ruta donde se va a redirigir el usuario si acepta el 
         * pago (setReturnUrl) o si se cancela (setCancelUrl)
         */
		$redirect_urls = new RedirectUrls();
		$redirect_urls->setReturnUrl(\URL::route('payment.status'))
            ->setCancelUrl(\URL::route('payment.status'));
        
        /** Objeto payment, a traves que el cual se va a realizar el pago 
         * y se configura el tipo de pago venta directa . 
         */

		$payment = new Payment();
		$payment->setIntent('Sale')
			->setPayer($payer)
			->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
            
        /*Ejecutar el metodo create 
        */    
		try {
			$payment->create($this->_api_context);
		} catch (\PayPal\Exception\PPConnectionException $ex) {
			if (\Config::get('app.debug')) {
                
                echo "Exception: " . $ex->getMessage() . PHP_EOL;
				$err_data = json_decode($ex->getData(), true);
				exit;
			} else {
				die('Ups! Algo salió mal');
			}
        }

        /**Si todo sale bien se redirige al usuario que se registre en paypal  */
        foreach($payment->getLinks() as $link) {
			if($link->getRel() == 'approval_url') {
				$redirect_url = $link->getHref();
				break;
			}
		}
		// add payment ID to session ID para darlle segimiento a un usuario 
		\Session::put('paypal_payment_id', $payment->getId());
		if(isset($redirect_url)) {
			// redirect to paypal
			return \Redirect::away($redirect_url);
		}
		return \Redirect::route('cart-show')
			->with('error', 'Ups! Error desconocido.');
    }

    /** Metodo que da respuesta paypal una vez el usuario haya iniciado sesion 
     * en paypal 
     */
    public function getPaymentStatus()
	{
		// Get the payment ID before session clear
        $payment_id = \Session::get('paypal_payment_id');
        
		// clear the session payment ID
		\Session::forget('paypal_payment_id');
		$payerId = \Input::get('PayerID');
		$token = \Input::get('token');
		//if (empty(\Input::get('PayerID')) || empty(\Input::get('token'))) {
		if (empty($payerId) || empty($token)) {
			return \Redirect::route('home')
				->with('message', 'Hubo un problema al intentar pagar con Paypal');
		}
		$payment = Payment::get($payment_id, $this->_api_context);
		// PaymentExecution object includes information necessary 
		// to execute a PayPal account payment. 
		// The payer_id is added to the request query parameters
		// when the user is redirected from paypal back to your site
		$execution = new PaymentExecution();
		$execution->setPayerId(\Input::get('PayerID'));
		//Execute the payment
		$result = $payment->execute($execution, $this->_api_context);
		//echo '<pre>';print_r($result);echo '</pre>';exit; // DEBUG RESULT, remove it later
		if ($result->getState() == 'approved') { // payment made
			// Registrar el pedido --- ok
			// Registrar el Detalle del pedido  --- ok
			// Eliminar carrito 
			// Enviar correo a user
			// Enviar correo a admin
            // Redireccionar
            
			$this->saveOrder(\Session::get('cart'));
			\Session::forget('cart');
			return \Redirect::route('home')
				->with('message', 'Compra realizada de forma correcta');
		}
		return \Redirect::route('home')
			->with('message', 'La compra fue cancelada');
    }
    
    private function saveOrder($cart)
	{
	    $subtotal = 0;
	    foreach($cart as $item){
	        $subtotal += $item->price * $item->quantity;
	    }
	    
	    $order = Order::create([
	        'subtotal' => $subtotal,
	        'shipping' => 100,
	        'user_id' => \Auth::user()->id
	    ]);
	    
	    foreach($cart as $item){
	        $this->saveOrderItem($item, $order->id);
	    }
	}
	
	private function saveOrderItem($item, $order_id)
	{
		OrderItem::create([
			'quantity' => $item->quantity,
			'price' => $item->price,
			'product_id' => $item->id,
			'order_id' => $order_id
		]);
    }
    
    public function mensaje(){
			$this->saveOrder(\Session::get('cart'));
			\Session::forget('cart');
        return \Redirect::route('home')
				->with('message', 'Compra realizada de forma correcta');
	}
	
	  public function cancelada(){
    	\Session::forget('cart');

       	return \Redirect::route('home')
			->with('message-error', 'La compra fue cancelada y el carrito se elimino  ');
    }



}
