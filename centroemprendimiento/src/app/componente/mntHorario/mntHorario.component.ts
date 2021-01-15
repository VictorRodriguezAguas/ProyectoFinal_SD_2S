import { AfterViewInit, Component, EventEmitter, Input, OnDestroy, OnInit, Output, ViewChild } from '@angular/core';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs';
import { HorarioAt } from 'src/app/interfaces/horarioat';
import { MensajeService } from 'src/app/servicio/Mensaje.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-mntHorario',
  templateUrl: './mntHorario.component.html',
  styleUrls: ['./mntHorario.component.css']
})
export class MntHorarioComponent implements OnInit, AfterViewInit, OnDestroy {

  @Input() titulo;
  @Input() horario: Horario[];
  @Output() grabar = new EventEmitter<any>();
  @Output() getHorarios = new EventEmitter<any>();

  txtDia = 'LUNES';
  txtHoraInicio = '18:00';
  txtHoraFin = '15:00';

  @ViewChild(DataTableDirective, { static: false }) dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject();

  constructor(private mensajeService: MensajeService) { }

  ngOnInit() {
  }

  agregarAHorario(): void {
    if (Date.parse('2020-01-01 ' + this.txtHoraInicio) > Date.parse('2020-01-01 ' + this.txtHoraFin)) {
      this.mensajeService.alertError(null, 'La fecha de inicio no puede ser menor a la fecha de fin');
    } else {
      // Si pasa el primer filtro validar que no sea un horario repetido o que se cruze
      this.horario.push({
        dia: this.txtDia, hora_inicio: this.txtHoraInicio, hora_fin: this.txtHoraFin
      });
      this.getHorarios.emit(this.horario);
      this.rerender();
    }
  }

  eliminarItem(i: number): void {
    Swal.fire({
      title: 'Eliminar el item?',
      text: 'Una vez eliminado no se podra recuperar la informacion',
      type: 'warning',
      showCloseButton: true,
      showCancelButton: true
    }).then((willDelete) => {
      if (willDelete.dismiss) {
      } else {
        this.horario.splice(i, 1);
        this.rerender();
        this.getHorarios.emit(this.horario);
      }
    });
  }

  guardarHorario() {
    this.grabar.emit(this.horario);
  }

  rerender(): void {
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
      //dtInstance.clear().draw();
      // Destroy the table first
      dtInstance.destroy();
      // Call the dtTrigger to rerender again
      this.dtTrigger.next();
    });
  }

  ngAfterViewInit(): void {
    this.dtTrigger.next();
  }

  ngOnDestroy(): void {
    // Do not forget to unsubscribe the event
    this.dtTrigger.unsubscribe();
  }
}

export interface Horario {
  dia: string;
  hora_inicio: string;
  hora_fin: string;
}
