<?php

class Voluntario
{
    private $id;
    private $nombre;
    private $rut;
    private $telefono;
    private $correo;
    private $profesion;
    private $region;
    private $id_region;
    private $comuna;
    private $experiencia_voluntario;
    private $experiencia_otra_emergencia;
    private $recursos_propios;
    private $hobbies;
    private $tipo_alimentacion;
    private $grupo_sanguineo;
    private $enfermedades_cronicas;
    private $actividades;
    private $area_desempeno;
    private $experiencia_emergencias;
    private $experiencia_animales;
    private $experiencia_desastres;
    private $certificado_titulo;
    private $estado;
    private $fecha_registro;
    private $fotoperfil;
    private $certificado_antecedentes;
    private $TypeUser;

    public function __construct($id,$nombre, $rut, $telefono, $correo,  $profesion, $region, $id_region, $comuna, $experiencia_voluntario, $experiencia_otra_emergencia, $recursos_propios, $hobbies, $tipo_alimentacion, $grupo_sanguineo, $enfermedades_cronicas, $actividades, 
    $area_desempeno, $experiencia_emergencias, $experiencia_animales, $experiencia_desastres, $certificado_titulo, $estado, $fecha_registro, $fotoperfil, 
    $certificado_antecedentes,$TypeUser)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->rut = $rut;
        $this->telefono = $telefono;
        $this->correo = $correo;
        $this->profesion = $profesion;
        $this->id_region = $id_region;
        $this->region = $region;
        $this->comuna = $comuna;
        $this->experiencia_voluntario = $experiencia_voluntario;
        $this->experiencia_otra_emergencia = $experiencia_otra_emergencia;
        $this->recursos_propios = $recursos_propios;
        $this->hobbies = $hobbies;
        $this->tipo_alimentacion = $tipo_alimentacion;
        $this->grupo_sanguineo = $grupo_sanguineo;
        $this->enfermedades_cronicas = $enfermedades_cronicas;
        $this->actividades = $actividades;
        $this->area_desempeno = $area_desempeno;
        $this->experiencia_emergencias = $experiencia_emergencias;
        $this->experiencia_animales = $experiencia_animales;
        $this->experiencia_desastres = $experiencia_desastres;
        $this->certificado_titulo = $certificado_titulo;
        $this->estado = $estado;
        $this->fecha_registro = $fecha_registro;
        $this->fotoperfil = $fotoperfil;
        $this->certificado_antecedentes = $certificado_antecedentes;
        $this->TypeUser = $TypeUser;
    }

    public function cambiar_telefono($telefono) {$this->telefono = $telefono; }
    public function cambiar_correo($correo) {$this->correo = $correo; }
    public function cambiar_fotoperfil($fotoperfil) {$this->fotoperfil = $fotoperfil; }
    public function cambiar_certificado_antecedentes($certificado_antecedentes) {$this->certificado_antecedentes = $certificado_antecedentes; }
    public function cambiar_certificado_titulo($certificado_titulo) {$this->certificado_titulo = $certificado_titulo; }
    public function cambiar_TypeUser($TypeUser) {$this->TypeUser = $TypeUser; }


    public function obtener_id(){ return $this->id; }
    public function obtener_nombre(){ return $this->nombre; }
    public function obtener_rut(){ return $this->rut; }
    public function obtener_telefono(){ return $this->telefono; }
    public function obtener_correo(){ return $this->correo; }
    public function obtener_profesion(){ return $this->profesion; }
    public function obtener_region(){ return $this->region; }
    public function obtener_id_region(){ return $this->id_region; }
    public function obtener_comuna(){ return $this->comuna; }
    public function obtener_experiencia_voluntario(){ return $this->experiencia_voluntario; }
    public function obtener_experiencia_otra_emergencia(){ return $this->experiencia_otra_emergencia; }
    public function obtener_recursos_propios(){ return $this->recursos_propios; }
    public function obtener_hobbies(){ return $this->hobbies; }
    public function obtener_tipo_alimentacion(){ return $this->tipo_alimentacion; }
    public function obtener_grupo_sanguineo(){ return $this->grupo_sanguineo; }
    public function obtener_enfermedades_cronicas(){ return $this->enfermedades_cronicas; }
    public function obtener_actividades(){ return $this->actividades; }
    public function obtener_area_desempeno(){ return $this->area_desempeno; }
    public function obtener_experiencia_emergencias(){ return $this->experiencia_emergencias; }
    public function obtener_experiencia_animales(){ return $this->experiencia_animales; }
    public function obtener_experiencia_desastres(){ return $this->experiencia_desastres; }
    public function obtener_certificado_titulo(){ return $this->certificado_titulo; }
    public function obtener_estado(){ return $this->estado; }
    public function obtener_fecha_registro(){ return $this->fecha_registro; }
    public function obtener_fotoperfil(){ return $this->fotoperfil; }
    public function obtener_certificado_antecedentes(){ return $this->certificado_antecedentes; }
    public function obtener_TypeUser(){ return $this->TypeUser; }
}

class Ubicaciones
{
    private $id;
    private $id_region;
    private $tipo;
    private $direccion;
    private $email;

    public function __construct($id, $id_region, $tipo, $direccion, $email)
    {
        $this->id = $id;
        $this->id_region = $id_region;
        $this->tipo = $tipo;
        $this->direccion = $direccion;
        $this->email = $email;
    }

    public function cambiar_tipo($tipo) { return $this->tipo = $tipo; }
    public function cambiar_direccion($direccion) { return $this->direccion = $direccion; }
    public function cambiar_email($email) { return $this->email = $email; }

    public function obtener_id() { return $this->id; }
    public function obtener_id_region() { return $this->id_region; }
    public function obtener_tipo() { return $this->tipo; }
    public function obtener_direccion() { return $this->direccion; }
    public function obtener_email() { return $this->email; }
}

class Asistencia
{
    private $id;
    private $id_voluntario;
    private $id_ubicacion;
    private $entrada;
    private $salida;

    public function __construct($id, $id_voluntario, $id_ubicacion, $entrada, $salida)
    {
        $this->id = $id;
        $this->id_voluntario = $id_voluntario;
        $this->id_ubicacion = $id_ubicacion;
        $this->entrada = $entrada;
        $this->salida = $salida;
    }

  
    public function obtener_id() { return $this->id; }
    public function obtener_id_voluntario() { return $this->id_voluntario; }
    public function obtener_id_ubicacion() { return $this->id_ubicacion; }
    public function obtener_entrada() { return $this->entrada; }
    public function obtener_salida() { return $this->salida; }
}

class Region
{
    private $id;
    private $region;

    public function __construct($id, $region)
    {
        $this->id = $id;
        $this->region = $region;
    }

    public function obtener_id() { return $this->id; }
    public function obtener_region() { return $this->region; }
}

class Consejo
{
    private $id;
    private $region_id;
    private $nombre;
    private $correo;
    private $nombre_coordinador;
    private $correo_coordinador;

    public function __construct($id, $region_id, $nombre, $correo, $nombre_coordinador, $correo_coordinador)
    {
        $this->id = $id;
        $this->region_id = $region_id;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->nombre_coordinador = $nombre_coordinador;
        $this->correo_coordinador = $correo_coordinador;
    }

    public function cambiar_nombre_coordinador($nombre_coordinador) { return $this->nombre_coordinador = $nombre_coordinador; }
    public function cambiar_correo_coordinador($correo_coordinador) { return $this->correo_coordinador = $correo_coordinador; }

    public function obtener_id() { return $this->id; }
    public function obtener_region_id() { return $this->region_id; }
    public function obtener_nombre() { return $this->nombre; }
    public function obtener_correo() { return $this->correo; }
    public function obtener_nombre_coordinador() { return $this->nombre_coordinador; }
    public function obtener_correo_coordinador() { return $this->correo_coordinador; }
}

class Credencial
{
    private $id;
    private $id_voluntario;
    private $institucion;
    private $cargo;

    public function __construct($id, $id_voluntario, $cargo, $institucion)
    {
        $this->id = $id;
        $this->id_voluntario = $id_voluntario;
        $this->institucion = $institucion;
        $this->cargo = $cargo;
    }

    public function cambiar_cargo($cargo) { return $this->cargo = $cargo; }
    public function cambiar_institucion($institucion) { return $this->institucion = $institucion; }

    public function obtener_id() { return $this->id; }
    public function obtener_id_voluntario() { return $this->id_voluntario; }
    public function obtener_institucion() { return $this->institucion; }
    public function obtener_cargo() { return $this->cargo; }
}

class Certificados
{
    private $id;
    private $id_voluntario;
    private $titulo;
    private $ubicacion;

    public function __construct($id, $id_voluntario, $titulo, $ubicacion)
    {
        $this->id = $id;
        $this->id_voluntario = $id_voluntario;
        $this->titulo = $titulo;
        $this->ubicacion = $ubicacion;
    }


    public function obtener_id() { return $this->id; }
    public function obtener_id_voluntario() { return $this->id_voluntario; }
    public function obtener_titulo() { return $this->titulo; }
    public function obtener_ubicacion() { return $this->ubicacion; }
}

?>
