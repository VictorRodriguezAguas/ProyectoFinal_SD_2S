import { Component, OnInit, ViewChild, ViewEncapsulation } from '@angular/core';

import { formatDate } from '@angular/common';
import { FullCalendarComponent } from '@fullcalendar/angular';
import { EventInput } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGrigPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

import 'sweetalert2/src/sweetalert2.scss';
import Swal from 'sweetalert2';
import { Agenda } from 'src/app/interfaces/agenda';
import { EmprendedorInter } from 'src/app/interfaces/Emprendedor';
import { Archivos } from 'src/app/interfaces/archivos';
import { Reunion } from 'src/app/interfaces/reunion';
import { AsistenteTecnico } from 'src/app/interfaces/asistentetecnico';
import { AsistentetecnicoService } from 'src/app/servicio/Asistentetecnico.service';
import { EmprendedorService } from 'src/app/servicio/Emprendedor.service';
import { AgendaService } from 'src/app/servicio/Agenda.service';
import { Mentor } from 'src/app/estructuras/mentor';
import { MentoriaService } from 'src/app/servicio/Mentoria.service';

@Component({
  selector: 'app-mesa-consulta-calendario',
  templateUrl: './mesa-consulta-calendario.component.html',
  styleUrls: ['./mesa-consulta-calendario.component.scss'],
  encapsulation: ViewEncapsulation.None
})
export class MesaConsultaCalendarioComponent implements OnInit {
  @ViewChild('calendar', { static: false }) calendarComponent: FullCalendarComponent; // the #calendar in the template

  agendaAT: Agenda[];
  calendar: FullCalendarComponent;
  // Estas variables son de "sesion" deben cambiar una vez que integremos el login
  idPersona = 1759;

  calendarVisible = true;
  calendarPlugins = [dayGridPlugin, timeGrigPlugin, interactionPlugin];
  calendarWeekends = true;
  allDaySlot = false;

  temas = '';
  acuerdos = '';
  observacion = '';
  codFormulario="";

  editable = false;

  public notSupported = false;

  calendarEvents: EventInput[] = []

  emprendedor: EmprendedorInter;
  archivos: Archivos[];
  reunion: Reunion[] = [];

  agendaSeleccionada: Agenda;

  asistentesTecnicos: AsistenteTecnico[] = [];
  asistenteSeleccionado: AsistenteTecnico;

  listaPersonas:any[]=[];

  mentores: Mentor[] = [];
  mentorSeleccionado: Mentor;

  personaSeleccionada:any;

  tipo = 'ASISTENCIA TECNICA';

  public showView = false;
  public showFR = false;
  public showIR = true;

  colorItem = '#3ebfea';

  constructor(
    private asistenteTecnicoService: AsistentetecnicoService,
    private emprendedorService: EmprendedorService,
    private agendaService: AgendaService,
    private mentoriaService: MentoriaService
  ) { }

  ngOnInit() {
    const isIE = /msie\s|trident\/|edge\//i.test(window.navigator.userAgent);
    if (isIE) {
      this.notSupported = true;
    }
    this.cambioTipo();
  }

  getAsistentesTecnicos(): void {
    this.asistenteTecnicoService.getAsistentesTecnicos()
      .subscribe(
        asistentesTecnicos => {
          //this.asistentesTecnicos = asistentesTecnicos.data
          //this.listaPersonas = asistentesTecnicos.data;
          this.setListaPersonas(asistentesTecnicos.data);
        }
      );
  }

  setListaPersonas(lista){
    this.listaPersonas = lista;
    this.listaPersonas.unshift({id:'-', nombre:'Selecciona', apellido: ''});
    this.listaPersonas.forEach(element => {
      element.value = element.id_persona;
      element.label = element.nombre + ' ' + element.apellido;
    });
  }

  getMentores(): void {
    this.mentoriaService.getMentores('A')
      .subscribe(
        data => {
          //this.mentores = data.data
          //this.listaPersonas = data.data;
          this.setListaPersonas(data.data);
        }
      );
  }

  id_persona;

  cambioTipo(){
    this.agendaAT = [];
    this.calendarEvents = [];
    this.listaPersonas=[];
    switch(this.tipo){
      case 'MENTORIA':
        this.getMentores();
        this.codFormulario = 'FRMENT01';
        break;
      case 'ASISTENCIA TECNICA':
        this.codFormulario = 'FRMAT001';
        this.getAsistentesTecnicos();
        break;
    }
  }
  
  getAgendamientoPersona(event): void {
    this.agendaAT = [];
    this.calendarEvents = [];
    this.agendaService.getAgendaxPersona2(this.id_persona, this.tipo)
    //this.asistenteTecnicoService.getAgendaAT(idPersona)
      .subscribe(
        respuesta => {
          if (respuesta.codigo == '1') {
            this.agendaAT = respuesta.data;
            if (this.agendaAT.length < 1) {
              this.calendarEvents = [];
            } else {
              this.agendaAT.forEach((agenda, index) => {
                if (agenda.id_reunion == null)
                this.colorItem = '#FFBA57';
              else
                (agenda.estado_reunion == 'EP') ? this.colorItem = '#4680FF' : this.colorItem = '#9CCC65';

                // console.log(formatDate(agenda.fecha_agenda + ' ' + agenda.hora_inicio_agenda, 'yyyy-MM-dd HH:mm', 'en-ES'));
                this.calendarEvents = this.calendarEvents.concat({ // add new event data. must create new array
                  title: agenda.persona1,
                  start: formatDate(agenda.fecha_agenda + ' ' + agenda.hora_inicio_agenda, 'yyyy-MM-dd HH:mm', 'en-US'),
                  end: formatDate(agenda.fecha_agenda + ' ' + agenda.hora_inicio_agenda, 'yyyy-MM-dd HH:mm', 'en-US'),
                  borderColor: '#3ebfea',
                  backgroundColor: this.colorItem,
                  textColor: '#fff',
                  extendedProps: {
                    id_agenda: agenda.id_agenda,
                    id_persona: agenda.id_persona,
                    indice_agenda: index,
                    tipo_asistencia: agenda.tipo_asistencia
                  },
                });
              });
            }
          }
        }
      );
  }

  handleDateClick(arg: any) {
    if (confirm('Would you like to add an event to ' + arg.dateStr + ' ?') && !this.notSupported) {
      this.calendarEvents = this.calendarEvents.concat({ // add new event data. must create new array
        title: 'New Event',
        start: arg.date,
        allDay: arg.allDay,
        borderColor: '#3ebfea',
        backgroundColor: '#3ebfea',
        textColor: '#fff'
      });
    }
  }

  handleEventClick(arg: any) {
    // console.log(arg);
    // console.log(arg.event.extendedProps.id_agenda);
    // console.log(arg.event.extendedProps.id_persona);
    this.getEmprendedor(arg.event.extendedProps.id_persona);
    this.getArchivos(arg.event.extendedProps.id_persona, 'MODELO_NEGOCIO');
    this.agendaSeleccionada = this.agendaAT[arg.event.extendedProps.indice_agenda];
    // console.log(this.agendaSeleccionada);
  }

  getEmprendedor(idPersona: number): void {
    this.emprendedorService.getEmprendedorAT(idPersona)
      .subscribe(
        respuesta => {
          if(respuesta.codigo=='1'){
            this.emprendedor = respuesta.data;
          }
          //this.showView = true;
        }
      );
  }

  getArchivos(idPersona: number, daemon: string): void {
    this.emprendedorService.getArchivos(idPersona, daemon)
      .subscribe(
        archivos => {
          this.archivos = archivos;
          this.showView = true;
        }
      );
  }

  eventRender(evento) {
    //evento.el.querySelectorAll(".fc-content")[0].setAttribute("data-tooltip", evento.event.title);
    evento.el.querySelectorAll(".fc-content")[0].setAttribute("data-tooltip", evento.event.extendedProps.tipo_asistencia);
    evento.el.querySelectorAll(".fc-content")[0].classList.add('fc-content-tooltip');
  }

}
