import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup } from '@angular/forms';
import { PanelforosService } from 'src/app/servicio/panelforos.service';
import { PersonaService } from "src/app/servicio/Persona.service";

import "../../../../node_modules/tinymce/tinymce.min.js";
import "../../interfaces/foros";
import { Foro } from '../../interfaces/foros';
@Component({
  selector: 'app-foros',
  templateUrl: './foros.component.html',
  styleUrls: ['./foros.component.scss']
})
export class ForosComponent implements OnInit {
  items = ['innovacíon', 'sostenibilidad', 'economía circular', 'digital', 'gastronomía'];
  public basicContent: string;
  foro: FormGroup;

  listaforos: Array<Foro>;


  dataforos: Array<any>;


  constructor(private PanelforosService: PanelforosService, private PersonaService: PersonaService) {
    this.basicContent = '<p>Hello...</p>';
  }



  ngOnInit() {
    this.foro = new FormGroup({
      temaforo: new FormControl(''),
      fecha_inicio: new FormControl(''),
      fecha_fin: new FormControl(''),
      prioridad: new FormControl(''),
      relacion: new FormControl(''),
      Tags: new FormControl(''),
      contenido: new FormControl(''),
    });

    this.cargarForo();
  }


  getPersona(id: any) {
    this.PersonaService.getPersona(id).subscribe(
      data => {
        return data.data.nombre;
      }
    );
  }

  crearForo() {

    this.listaforos.push(this.foro.value);
  }

  async cargarForo() {
    this.listaforos = new Array();
    const foro: Foro = {
      tema: "prueba",
      fecha_inicio: "12/10/27",
      fecha_fin: "12/10/27",
      prioridad: "Urgente",
      relacion: "Tecnologia",
      Tags: ['innovacíon', 'sostenibilidad', 'economía circular', 'digital', 'gastronomía'],
      contenido: "",
      usuarioimg: "assets/images/user/avatar-3.jpg",
      usuario: "Alvaro Valarezo",
      status: "ocupado"
    }
    this.listaforos.push(foro);

    var tematica = { "1": "Tecnologia", "2": "Negocios", "3": "Marketing Digital" };
    var prioridad = { "1": "Baja", "2": "Media", "3": "Alta", "4": "Urgente" };

    this.PanelforosService.getForos().subscribe(
      data => {
        console.log(data.data[0].id_prioridad);

        data.data.forEach(element => {
          var numero = element.id_prioridad;
          var numerotema = element.id_tipo_tematica;
          element.id_prioridad = prioridad[numero];
          element.id_tipo_tematica = tematica[numerotema];

        });
        this.listaforos = data.data;
      }

    );


  }


}
