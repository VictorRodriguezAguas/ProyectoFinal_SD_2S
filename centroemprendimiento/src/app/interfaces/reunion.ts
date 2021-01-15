export interface Reunion {
  id_reunion: number;
  id_agenda: number;
  hora_inicio_agenda: string;
  hora_fin_agendad: string;
  fecha_agendada: string;
  hora_inicio: string;
  hora_fin: string;
  archivo:string;
  temas: string;
  acuerdos: string;
  observacion: string;
  estado: string;
  tipo_reunion: string;
  url_reunion: string;
  id_actividad_inscripcion: string;
  id_registro_formulario: number;

  url_imagen_inicio?:string;
  url_imagen_fin?:string;

  actividades_asignadas?:any[];
  mentorias_asignadas?:any[];
}