import { Component, Input, OnInit } from '@angular/core';
import { Persona } from 'src/app/estructuras/persona';

@Component({
  selector: 'app-avancePerfil',
  templateUrl: './avancePerfil.component.html',
  styleUrls: ['./avancePerfil.component.scss']
})
export class AvancePerfilComponent implements OnInit {

  @Input() persona:Persona;
  
  constructor() { }

  ngOnInit() {
  }

}
