<?php

	class Conexion extends PDO
	{

   public $ConexSQL;
   private $my_tipo_db = 'mysql';
   private $my_host = 'localhost';
   private $my_db = 'hicom';
   private $my_user = 'root';
   private $my_pass = 'SuperPassUser'; //SuperPassUser
   
   public function __construct() {
      //Sobreescribo el método constructor de la clase PDO.
      try{

         $this->ConexSQL = new PDO($this->my_tipo_db.':host='.$this->my_host.';dbname='.$this->my_db, $this->my_user, $this->my_pass);

      }catch(PDOException $e){
         ?>
            <article style="max-width: 450px; padding: 0 10px; margin: 100px auto; overflow: hidden; text-align: center; font-size: 24px; font-family: arial;">
                <i class="fas fa-exclamation-triangle" style=" display: inline-block; font-size: 70px; color: red; padding-bottom: 20px;"></i>
                <br />
                ¡Error! no se puede conectar a la base de datos, intentelo nuevamente, si el problema persiste comuniquese con el desarrollador del sistema.
            </article>
         <?php
         exit;
         				
			}//fin try catch

		}//fin construct

	}//fin class

?>