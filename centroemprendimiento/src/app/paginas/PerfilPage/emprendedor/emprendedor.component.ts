import { Component, OnInit, Input, AfterViewInit } from '@angular/core';
import { Usuario } from 'src/app/estructuras/usuario';
import { Persona } from 'src/app/estructuras/persona';
import { Emprendedor } from 'src/app/estructuras/emprendedor';
import { Emprendimiento } from 'src/app/estructuras/emprendimiento';
import { General } from 'src/app/estructuras/General';
import { Inscripcion } from 'src/app/estructuras/inscripcion';
import { ProgramaService } from 'src/app/servicio/Programa.service';
import { EventoService } from 'src/app/servicio/Evento.service';
import { MensajeService } from 'src/app/servicio/Mensaje.service';
import { DashboardService } from 'src/app/servicio/Dashboard.service';

@Component({
  selector: 'app-emprendedor',
  templateUrl: './emprendedor.component.html',
  styleUrls: ['./emprendedor.component.scss']
})
export class EmprendedorComponent implements OnInit, AfterViewInit {

  /*Entidades */
  usuario:Usuario;
  @Input() persona: Persona;
  @Input() emprendedor: Emprendedor = new Emprendedor();
  @Input() emprendimiento: Emprendimiento = new Emprendimiento();
  @Input() listaEmprendimientos: Emprendimiento[];

  listaProgramasInscritos: Inscripcion[];
  actividad_selecionada = {};
  programa_selecionada = {};

  etapas = 4;
  etapa = 3;
  etapaText = "";
  resumen = {completadas:0, total: 0, porAvance:0, 
            tallesTotal: 0, tallesHabilitados: 0, talleresAvance:0,
            actividadesHabilitadas: 0, actividadesHabilitadasAvance: 0,
            actividadesInhabilitadas:0, actividadesInhabilitadasAvance:0};

  public activeTab: string;

  constructor(
    private programaService: ProgramaService,
    private dashboardService: DashboardService,
    private eventoService: EventoService,
    private mensajeService: MensajeService) {
    this.usuario = Usuario.getUser();
    this.activeTab = 'home';
  }

  ngOnInit() {
    this.getProgramasInscritos();
    this.consultarTalleres();
    this.getResumen();
    let data = {estado:'A', id_tipo_tematica:'1', 'id_prioridad':1, 'nombre':'pepito',fecha_desde:'2020-12-01',fecha_hasta:'2020-12-12',tema:'hola'};
    console.log(JSON.stringify(data));
  }

  ngAfterViewInit(){
    document.querySelector('.nav-pills').classList.add('navemp');
  }

  getId(){
    return General.generateId();
  }

  getProgramasInscritos():void{
    this.programaService.getProgramas(this.persona.id_persona).subscribe(data =>{
      console.log(data);
      if(data.codigo == '1'){
        this.listaProgramasInscritos = data.data as Inscripcion[];
        let levantarHome=false;
        this.listaProgramasInscritos.forEach(element => {
          if(!levantarHome){
            element.actividades.forEach(act => {
              if(act.id_tipo_actividad == 8 && act.estado_actividad_inscripcion == 'IN'){
                element.levantarHome = true;
                levantarHome = true;
              }
              if(act.estado_actividad_inscripcion){
                this.resumen.actividadesHabilitadas++;
              }else{
                this.resumen.actividadesInhabilitadas++;
              }
              if(act.id_tipo_actividad == 12){
                this.resumen.tallesTotal++;
                if(act.estado_actividad_inscripcion){
                  this.resumen.tallesHabilitados++;
                }
              }
            });
            this.resumen.talleresAvance = Math.round(this.resumen.tallesHabilitados / this.resumen.tallesTotal * 100);
            if(this.resumen.total > 0){
              this.resumen.actividadesHabilitadasAvance = Math.round(this.resumen.actividadesHabilitadas / this.resumen.total * 100);
              this.resumen.actividadesInhabilitadasAvance = Math.round(this.resumen.actividadesInhabilitadas / this.resumen.total * 100);
            }
            element.actividades = this.programaService.armarArbolActividades(element.actividades, null);
          }
        });
      }
    });
  }

  talleres;

  consultarTalleres(){
    if(!this.persona){
      setTimeout(() => {
        this.consultarTalleres();
      }, 500);
      return;
    }
    this.eventoService.getEventos({estado:'A', id_persona:this.persona.id_persona}).subscribe(data=>{
      if(data.codigo=="1"){
        if(data.data){
          data.data.forEach(item => {
            item.stick = true;
            item.title = item.nombre;
            item.start = item.fecha + 'T'+item.hora_inicio;
            item.end = item.fecha + 'T'+item.hora_fin;
            item.url1=null;
            item.url=undefined;
          });
        }
        this.talleres = data.data;
      }
      document.querySelector('.nav-pills').classList.add('navemp');
    });
  }

  getResumen(){
    this.dashboardService.getResumenEmprendedor().subscribe(data=>{
      if(data.codigo=="1"){
        this.etapa = data.data.inscripcion.etapa;
        this.etapas = data.data.inscripcion.etapas;
        this.etapaText = data.data.inscripcion.etapaText;
        //this.resumen = data.data.resumen;
        this.resumen.completadas = data.data.resumen.completadas;
        this.resumen.porAvance = data.data.resumen.porAvance;
        this.resumen.total = data.data.resumen.total;
        /*this.resumen.tallesTotal = 0;
        this.resumen.tallesHabilitados = 0;
        this.resumen.actividadesHabilitadas = 0;
        this.resumen.actividadesInhabilitadas = 0;*/
        if(this.resumen.total > 0){
          this.resumen.actividadesHabilitadasAvance = Math.round(this.resumen.actividadesHabilitadas / this.resumen.total * 100);
          this.resumen.actividadesInhabilitadasAvance = Math.round(this.resumen.actividadesInhabilitadas / this.resumen.total * 100);
        }
      }
    });
  }

  clickEvent(evento){
    this.mensajeService.alertInfo(null, 'Puedes regístrarte a este taller en la sección "Actividades"');
  }

  calcularTotales(){
    
  }
}
