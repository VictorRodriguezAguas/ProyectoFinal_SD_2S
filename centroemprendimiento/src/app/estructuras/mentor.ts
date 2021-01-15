import { Periodo } from '../componente/mntPeriodo/mntPeriodo.component';
import { Persona } from './persona';

export class Mentor extends Persona {

    id_mentor:number=null;
    descripcion_perfil:string=null;
    descripcion_motivacion_mentor:string=null;
    horarios:HorarioMentor[]=[];
    dias_disponibilidad:string=null;
    max_horas_semana:number=null;
    max_horas_mes:number=null;
    id_edicion:number=null;
    fecha_inicio:string=null;
    fecha_fin:string=null;
    opinion_red_mentores:string;
    estado:string=null;
    id_usuario_aprob_rech:number=null;
    fecha_aprobacion:string=null;
    listaEjeMentoria:any[];
    periodos: PeriodoMentor[];
    evaluacion:any;

    constructor(mentor?) {
        super(mentor);
        if(mentor){
            this.id_mentor = mentor.id_mentor;
            this.descripcion_perfil = mentor.descripcion_perfil;
            this.descripcion_motivacion_mentor = mentor.descripcion_motivacion_mentor;
            this.horarios = mentor.horarios;
            this.dias_disponibilidad = mentor.dias_disponibilidad;
            this.max_horas_semana = mentor.max_horas_semana;
            this.max_horas_mes = mentor.max_horas_mes;
            this.id_edicion = mentor.id_edicion;
            this.fecha_inicio = mentor.fecha_inicio;
            this.fecha_fin = mentor.fecha_fin;
            this.opinion_red_mentores = mentor.opinion_red_mentores;
            this.estado = mentor.estado;
            this.id_usuario_aprob_rech = mentor.id_usuario_aprob_rech;
            this.fecha_aprobacion = mentor.fecha_aprobacion;
            this.listaEjeMentoria = mentor.listaEjeMentoria;
        }
    }
}

export interface HorarioMentor {
    id_mentor: number;
    dia: string;
    hora_inicio: string;
    hora_fin: string;
}

export interface PeriodoMentor extends Periodo {
    id_mentor: number;
    id_periodo: number;
    id_edicion: number;
}