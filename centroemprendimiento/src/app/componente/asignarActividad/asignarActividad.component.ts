import { Component, Input, OnInit, Output, EventEmitter, ViewChild, AfterViewInit } from '@angular/core';
import { DragAndDropEventArgs } from '@syncfusion/ej2-angular-navigations';
import { WizardComponent } from 'angular-archwizard';
import { CatalogoService } from 'src/app/servicio/catalogo.service';
import { MensajeService } from 'src/app/servicio/Mensaje.service';
import { ProgramaService } from 'src/app/servicio/Programa.service';

@Component({
    selector: 'app-asignarActividad',
    templateUrl: './asignarActividad.component.html',
    styleUrls: ['./asignarActividad.component.scss']
})
export class AsignarActividadComponent implements OnInit, AfterViewInit {

    /* Actividades de la etapa*/

    public field;

    constructor(private mensajeService: MensajeService,
        private programaService: ProgramaService,
        private catalogoService: CatalogoService) {
    }

    @Input() listaActividad: Object[];
    @Input() id_reunion: number;
    @Output() getData = new EventEmitter<any>();
    @Output() grabar = new EventEmitter<any>();
    @Output() cancelar = new EventEmitter<any>();
    // maps the appropriate column to fields property
    public allowDragAndDrop: boolean = true;
    allowMultiSelection = true;

    public nodeDrag(args: DragAndDropEventArgs): void {
        if (args.droppedNodeData) {
            if (args.droppedNodeData.parentID) {
                if (parseInt(args.droppedNodeData.parentID.toString()) > 0) {
                    args.dropIndicator = 'e-no-drop';
                }
            } else {
                if (parseInt(args.droppedNodeData.id.toString()) > 0) {
                    args.dropIndicator = 'e-no-drop';
                }
            }
        }
    }

    public dragStop(args: DragAndDropEventArgs): void {
        args.cancel = this.validaDrag(args);
    }

    validaDrag(args: DragAndDropEventArgs) {
        let novalido = true;
        if (args.droppedNodeData) {
            let parentDragID = 1;
            let parentDropID = 1;
            let dragID = 1;
            let dropID = 1;
            dragID = parseInt(args.draggedNodeData.id.toString());
            dropID = parseInt(args.droppedNodeData.id.toString());
            if (args.draggedNodeData.parentID) {
                parentDragID = parseInt(args.draggedNodeData.parentID.toString());
            }
            if (args.droppedNodeData.parentID) {
                parentDropID = parseInt(args.droppedNodeData.parentID.toString());
            }
            if (dropID == 0 && parentDragID <= 0) {
                novalido = false;
            }
            if (parentDragID <= 0 && parentDropID == 0) {
                novalido = false;
            }
            if (parentDragID == 0 && parentDropID < 0) {
                novalido = false;
            }
            if (dropID < 0 && parentDragID == 0) {
                novalido = false;
            }
            if (args.dropLevel != 2) {
                novalido = true;
            }
        }
        return novalido;
    }

    ngOnInit() {
        this.asignarActividades();
    }

    changeDataSource(respuesta) {
        this.getData.emit(respuesta);
    }


    /* Actividades por asignar*/
    @Input() id_persona;
    @Input() id_sub_programa=null;
    @Input() id_etapa = null;

    programa;
    @ViewChild(WizardComponent) private wizard: WizardComponent;
    @ViewChild('asignarActividad', { static: false }) private asignarActividad;

    levantarModal=true;

    ngAfterViewInit() {
        setTimeout(() => {
            if(this.levantarModal)
                this.asignarActividad.show();
        }, 500);
    }

    asignarActividades() {
        this.listaActividadesAsignadas = null;
        this.listaActividadesNew = null;
        this.programaService.getProgramaInscrito(this.id_sub_programa, this.id_etapa, this.id_persona).subscribe(data => {
            if (data.codigo == '1') {
                switch(data.data.inscripcionEtapa.estado){
                    /*case 'PF':
                        this.levantarModal = false;
                        this.mensajeService.alertError(null, 'Ya se ha realizado asignaciones de actividades en esta etapa al emprendedor');
                        this.cancelar.emit();
                        break;*/
                    case 'AP':
                        this.levantarModal = false;
                        this.mensajeService.alertError(null, 'No se puede asignar actividades porque la etapa se encuentra aprobada');
                        this.cancelar.emit();
                        break;
                    case 'CF':
                        this.levantarModal = false;
                        this.mensajeService.alertError(null, 'No se puede asignar actividades porque a cambiado de fase');
                        this.cancelar.emit();
                        break
                    default:
                        this.levantarModal = true;
                        break;
                }
                data.data.actividades.forEach(element => {
                    if (element.id_actividad_inscripcion == null) {
                        this.idAux--;
                        element.id_actividad_inscripcion = this.idAux;
                    }
                });
                this.programa = data.data;
                this.listaActividadesIni = data.data.actividades;
                this.listaActividadesAsignadas = this.programaService.armarArbolActividades(data.data.actividades, null);
                this.field = {
                    dataSource: this.listaActividadesAsignadas,
                    id: 'id_actividad_inscripcion',
                    parentID: 'id_actividad_padre',
                    text: 'actividad',
                    hasChildren: 'is_padre',
                    selected: 'isSelected'
                };
            }
            //this.listaActividadesAsignadas.push({id_actividad_inscripcion:"0", id_actividad_padre: null, actividad: "Nuevas actividades", is_padre:false, isSelected: true, child:[]});
        });
        this.catalogoService.getListaActividadesSubPrograma(this.id_sub_programa).subscribe(data => {
            if (data.codigo == '1') {
                let lista = [];
                let etapaAnt = "";
                let idEtapaAnt = 0;
                let etapa;
                data.data.forEach(element => {
                    element.id_actividad_inscripcion = '0';
                    element.item = element.actividad;
                    element.expandable = true;
                    element.is_agregada_manual = 'NO';
                    if (etapaAnt != element.etapa) {
                        idEtapaAnt--;
                        etapa = {};
                        etapa.actividad = element.etapa;
                        etapa.estado = 'A';
                        etapa.etapa = element.etapa;
                        etapa.expandible = true;
                        etapa.id = idEtapaAnt;
                        etapa.actividad = element.etapa;
                        etapa.id_actividad_etapa = idEtapaAnt;
                        etapa.id_actividad_inscripcion = idEtapaAnt;
                        etapa.id_actividad_padre = null;
                        etapa.id_etapa = element.id_etapa;
                        etapa.id_sub_programa = element.id_sub_programa;
                        etapa.id_tipo_actividad = idEtapaAnt;
                        etapa.is_agregada_manual = "NO";
                        etapa.item = element.etapa;
                        etapa.nombre = element.etapa;
                        etapa.sub_programa = element.sub_programa;
                        etapa.tipo_actividad = "";
                        lista.push(etapa);
                        etapaAnt = element.etapa;
                    }
                    if (!element.id_actividad_padre) {
                        element.id_actividad_padre = idEtapaAnt;
                    }
                    lista.push(element);
                });
                this.listaActividades = lista;
                this.listaActividadesNew = this.programaService.armarArbolActividades(lista, null, "children");
            }
        });
    }

    actividadesOrdenadas: any[];
    listaOrdenada;
    listaActividadesAsignadas;
    listaActividades;
    listaActividadesNew;
    listaActividadesIni;
    public fieldAsignacion: Object;
    idAux = -1;

    listaActividadesS;
    listaActividadesAlmacenar;

    listaActividadesOrden;
    setActividades(actividades) {
        this.listaActividadesS = actividades;
    }

    grabarAsignacion() {
        this.actividadesOrdenadas = [];
        this.armarOrdenActividades(this.listaOrdenada, null);
        this.programaService.asignarActividades(this.listaOrdenada, this.programa.sub_programa.id_inscripcion, this.id_reunion).subscribe(data => {
            if (data.codigo == '1') {
                this.asignarActividad.hide();
                this.mensajeService.alertOK(null, 'Actividades asignadas con éxito').then(()=>{
                    this.grabar.emit(data.data);
                });
            } else {
                this.mensajeService.alertError(null, data.mensaje);
            }
        });
    }

    armarOrdenActividades(lista, id_padre) {
        let orden = 1;
        lista.forEach(item => {
            item.antecesor = null;
            item.predecesor = null;
            if (orden > 1) {
                item.antecesor = lista[orden - 2].id_actividad_inscripcion;
            }
            if (orden < lista.length) {
                item.predecesor = lista[orden].id_actividad_inscripcion;
            }
            item.orden = orden;
            item.id_actividad_inscripcion_padre = id_padre;
            item.child = this.armarOrdenActividades(item.child, item.id_actividad_inscripcion);
            orden++;
            this.actividadesOrdenadas.push(item);
        });
        return lista;
    }

    getOrden(lista) {
        this.listaOrdenada = lista.data;
    }

    unirActividades() {
        if (!this.listaActividadesS) {
            this.mensajeService.alertError(null, 'Debe seleccionar por lo menos una actividad');
            return;
        }
        if (this.listaActividadesS.length == 0) {
            this.mensajeService.alertError(null, 'Debe seleccionar por lo menos una actividad');
            return;
        }
        this.listaActividadesOrden = null;
        let listaActividadesOrden = [];
        listaActividadesOrden.push({ id: -1, id_actividad_etapa: -1, id_actividad_inscripcion: -1, id_actividad_padre: null, actividad: "Nuevas actividades", is_padre: true, isSelected: true, child: [] });
        let i = 0;
        this.listaActividadesS.forEach(element => {
            if (element.selected && element.id > 0) {
                let found = this.listaActividades.find(item => item.id == element.id && item.id_tipo_actividad != 13);
                if (found) {
                    //if(found.id_actividad_padre < 0)
                    found.id_actividad_padre = -1;
                    found.is_agregada_manual = 'SI';
                    this.idAux--;
                    found.id_actividad_inscripcion = this.idAux;
                    listaActividadesOrden.push(found);
                    i++;
                }
            }
        });
        this.listaActividadesAlmacenar = listaActividadesOrden;
        this.listaActividadesOrden = this.listaActividadesAsignadas.concat(this.programaService._armarArbolActividadesAux(this.listaActividadesAlmacenar, null, "child"));
        this.listaOrdenada = this.listaActividadesOrden;
        this.fieldAsignacion = {
            dataSource: this.listaActividadesOrden,
            id: 'id_actividad_inscripcion',
            parentID: 'id_actividad_padre',
            text: 'actividad',
            hasChildren: 'is_padre',
            selected: 'isSelected'
        };
        this.wizard.goToNextStep();
    }

    _cancelar() {
        this.asignarActividad.hide();
        this.cancelar.emit();
    }
}
