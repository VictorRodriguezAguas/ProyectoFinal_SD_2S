export interface Mentoria {
  id_persona:number;
  id_mentor:number;
  id_eje_mentoria:number;
  nombre_emprendedor?:string;
  nombre_mentor?:string;
  tema_mentoria?:string;
  mentor?:any;
  mentorAnt?:any;
  cant:number;

  id_inscripcion:number;
  id_etapa:number;
  id_inscripcion_etapa:number;
}
