export interface Formulario {
    id_formulario:number;
    nombre:string;
    descripcion:string;
    estado:string;
    codigo:string;
    campos: Campo[]

    fecha_registro?:string;
    id_usuario_registro?:number;
    id_registro_formulario?:number;

    valido:boolean;
    submit:boolean;
}

export interface Campo{
    id_formulario:number;
    id_campo:number;
    obligatorio:string;
    dependiente?:number;
    tipo_seleccion:string;
    pregunta:string;
    estado:string;
    orden:number;

    nombre:string;
    descripcion:string;
    tipo:string;
    tabla:string;
    lista_value:string;
    campo_tabla:string;
    nemonico_file:string;
    codigo:string;
    campos_filtro:string;
    id_campo_otro:string;

    id_formulario_campo:number;
    id_registro:number;
    valor:any;
    otro_valor:any;

    valorAux:any;
    valido:boolean;
    submit:boolean;

    lista:any[];
}