<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
      //HACER referencia a  la tabla 
    
    protected $table ='categories';
    //Referencia primarykey
    protected $primaryKey = 'id'; 
    
    //laravel agrega dos columnas para saber cuando a sido modificado de creacion y actualizacion del registro
    
    //En este caso no es necesario 
    public $timestamps = true; 
    
    //declarar campos que van a recibir un valor para almacenarlos en la base de datos 
    
    protected $fillable =[
        'name',
        'slug',
        'description',
        'color'
    ]; 
    //cuando no queremos que los campos se asignen al modelo
     protected $guarded =[
        
        
    ]; 


    /*public function articulos()
    {
        return $this->hasMany(Articulo::class);//categoria_id

        //return $this->belongsTo(Categoria::class, $foreingKey);
    }*/
}
