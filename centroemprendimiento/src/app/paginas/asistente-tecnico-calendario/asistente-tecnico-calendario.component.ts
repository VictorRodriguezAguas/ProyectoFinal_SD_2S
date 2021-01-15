import { Component, OnInit, ViewChild, ViewEncapsulation } from '@angular/core';


import { formatDate } from '@angular/common';
import { FullCalendarComponent } from '@fullcalendar/angular';
import { EventInput } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGrigPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

import 'sweetalert2/src/sweetalert2.scss';
import { Agenda } from 'src/app/interfaces/agenda';
import { EmprendedorInter } from 'src/app/interfaces/Emprendedor';
import { Archivos } from 'src/app/interfaces/archivos';
import { Reunion } from 'src/app/interfaces/reunion';
import { AsistentetecnicoService } from 'src/app/servicio/Asistentetecnico.service';
import { Usuario } from 'src/app/estructuras/usuario';
import { Persona } from 'src/app/estructuras/persona';
import { Router } from '@angular/router';


@Component({
  selector: 'app-asistente-tecnico-calendario',
  templateUrl: './asistente-tecnico-calendario.component.html',
  styleUrls: ['./asistente-tecnico-calendario.component.scss'],
  encapsulation: ViewEncapsulation.None
})

export class AsistenteTecnicoCalendarioComponent implements OnInit {
  @ViewChild('calendar', { static: false }) calendarComponent: FullCalendarComponent; // the #calendar in the template 

  user = Usuario.getUser();

  agendaAT: Agenda[];
  calendar: FullCalendarComponent;

  calendarVisible = true;
  calendarPlugins = [dayGridPlugin, timeGrigPlugin, interactionPlugin];
  calendarWeekends = true;
  allDaySlot = false;


  colorItem = '#3ebfea';

  public notSupported = false;
  public todoListMessage: string;
  public todo_list_message_error: boolean;
  public newTodoList: any;

  dateObj = new Date();
  yearMonth = this.dateObj.getUTCFullYear() + '-' + (this.dateObj.getUTCMonth() + 1);

  calendarEvents: EventInput[] = []


  emprendedor: EmprendedorInter;
  archivos: Archivos[];
  reunion: Reunion;
  persona: Persona;
  agendaSeleccionada: Agenda;


  constructor(
    private asistenteTecnicoService: AsistentetecnicoService,
    private router: Router
  ) { }

  ngOnInit() {
    const isIE = /msie\s|trident\/|edge\//i.test(window.navigator.userAgent);
    if (isIE) {
      this.notSupported = true;
    }
    this.getAsistenteTecnicoAgenda(this.user.id_persona);
  }

  getAsistenteTecnicoAgenda(idPersona: number): void {
    this.agendaAT = [];
    this.asistenteTecnicoService.getAgendaAT(idPersona)
      .subscribe(
        respuesta => {
          if (respuesta.codigo == '1') {
            this.agendaAT = respuesta.data;
            this.agendaAT.forEach((agenda, index) => {
              if (agenda.id_reunion == null)
                this.colorItem = '#FFBA57';
              else
                (agenda.estado_reunion == 'EP') ? this.colorItem = '#4680FF' : this.colorItem = '#9CCC65';

              let tipo_asistencia = agenda.tipo_asistencia ? agenda.tipo_asistencia : 'Sin modalidad';

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
                  tipo_asistencia: tipo_asistencia
                },
              });
            });
          }
        }
      );
  }

  handleDateClick(arg: any) { }

  handleEventClick(arg: any) {
    let externalProperties;
    if (arg.event.extendedProps)
      externalProperties = arg.event.extendedProps;
    else
      externalProperties = arg.event._def.extendedProps;
    this.agendaSeleccionada = this.agendaAT[externalProperties.indice_agenda];
    this.router.navigate(['/agenda/reunion/' + this.agendaSeleccionada.id_agenda + '/3/FRMAT001/1']);
  }

  eventRender(evento) {
    //evento.el.querySelectorAll(".fc-content")[0].setAttribute("data-tooltip", evento.event.title);
    evento.el.querySelectorAll(".fc-content")[0].setAttribute("data-tooltip", evento.event.extendedProps.tipo_asistencia);
    evento.el.querySelectorAll(".fc-content")[0].classList.add('fc-content-tooltip');
  }

}
