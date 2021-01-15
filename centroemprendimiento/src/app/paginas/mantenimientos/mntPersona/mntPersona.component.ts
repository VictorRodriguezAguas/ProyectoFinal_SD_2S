import { AfterViewInit, Component, OnDestroy, OnInit, ViewChild } from '@angular/core';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs';
import { Mantenimiento } from 'src/app/interfaces/Mantenimiento';
import { PersonaService } from 'src/app/servicio/Persona.service';
import { Persona } from 'src/app/estructuras/persona';
import Swal from 'sweetalert2/dist/sweetalert2.js';
import { MensajeService } from 'src/app/servicio/Mensaje.service';

@Component({
  selector: 'app-mntPersona',
  templateUrl: './mntPersona.component.html',
  styleUrls: ['./mntPersona.component.css']
})
export class MntPersonaComponent extends Mantenimiento implements OnInit, AfterViewInit, OnDestroy {

  cod_trama: string = "MXUSU001";
  birthDateStr: string = '';
  nombre: string = "";
  estado: string = "T";
  isCreate: boolean = false;
  persona: Persona;
  usuario;
  userMail;
  birthDate;

  tabla = "persona";
  campos = ["nombre", "estado", "fecha", "hora_inicio", "hora_fin", "url", "id_tipo_evento", "codigo", "id_evento_mec", "color", "cupo"];
  camposLista = [{ attr: "id", name: "Id" }, { attr: "nombre", name: "Evento" }, { attr: "tipo_evento", name: "Tipo evento" }, { attr: "codigo", name: "Codigo padre" }, { attr: "color", name: "Color" }];

  
  @ViewChild('horarioModal', { static: false }) private horarioModal;
  @ViewChild(DataTableDirective, { static: false }) dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject();

  constructor(
    private personaService: PersonaService,
    private mensajeService: MensajeService) {
    super();
  }

  ngOnInit(): void {
    this.dtOptions = {
      pagingType: 'full_numbers'
    };
    this.consultarDatos();
  }

  consultarDatos() {
    this.personaService.getPersonas()
      .subscribe(data => {
        if (data.codigo == '1') {
          this.lista = data.data;
          this.rerender();
        } else {
          console.log(data.mensaje);
        }
      });
  }

  ngAfterViewInit(): void {
    this.dtTrigger.next();
  }

  ngOnDestroy(): void {
    // Do not forget to unsubscribe the event
    this.dtTrigger.unsubscribe();
  }

  rerender(): void {
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
      dtInstance.clear().draw();
      // Destroy the table first
      dtInstance.destroy();
      // Call the dtTrigger to rerender again
      this.dtTrigger.next();
    });
  }

  setData(registro) {
    this.registro = registro;
  }

  grabar(registro){
    this.consultarDatos();
  }

  eliminar(dato) {
    this.registro = dato;

    Swal.fire({
      title: 'Confirmación!',
      text: "¿Está seguro que desea eliminar?",
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Eliminar.',
      cancelButtonText: 'No, Declinar.'
    }).then((result) => {
      if (result.value) {
        this.registro.estado = 'I';
        this.personaService.grabarPersona(this.registro)
        .subscribe(data => {
          if (data.codigo == '1') {
            Swal.fire('Eliminado!','Su registro ha sido eliminado.','success');
          } else {
            this.mensajeService.alertError(null, data.mensaje);
          }
        });
      }
    });
  }

  editar(dato) {
    this.isCreate = false;
    this.registro = dato;
  }

  nuevo() {
    this.isCreate = true;
    this.registro = {};
    this.campos.forEach(item => {
      this.registro[item] = null;
    });
  }

  cancelar(persona){
    this.registro = null;
  }
}  