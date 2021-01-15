import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, Validators } from '@angular/forms';
import { Emprendedor } from 'src/app/estructuras/emprendedor';
import { Emprendimiento } from 'src/app/estructuras/emprendimiento';
import { Persona } from 'src/app/estructuras/persona';
import { validarEdadMin } from 'src/app/validators/validadores';

@Component({
  selector: 'app-registro',
  templateUrl: './registro.component.html',
  styleUrls: ['./registro.component.scss']
})
export class RegistroComponent implements OnInit {

  persona: Persona = new Persona();
  emprendedor: Emprendedor = new Emprendedor();
  emprendimiento: Emprendimiento = new Emprendimiento();

  constructor(private formBuilder: FormBuilder) { }

  formularioRegistro

  ngOnInit() {
    this.formularioRegistro = this.formBuilder.group({
      nombre: ['', Validators.required],
      tipo_identificacion: ['', Validators.required],
      identificacion: ['', Validators.required],
      apellido: ['', Validators.required],
      genero: ['', Validators.required],
      fecha_nacimiento: ['', { validators: [Validators.required], updateOn: "blur" }],

      //emprendimiento_formalizado: ['', Validators.required],
      telefono: ['', Validators.required],
      email: ['', Validators.required],
      provincia: ['', Validators.required],
      ciudad: ['', Validators.required],
      nivel_academico: ['', Validators.required],
      situacion_laboral: ['', Validators.required],
      direccion: ['', Validators.required],
      nombre_emprendimiento: ['', Validators.required],

      tipo_emprendimiento: ['', Validators.required],
      aceptar_uso_datos: new FormControl(),
      aceptar_compromiso_participante: new FormControl(),
      telefono_fijo: new FormControl()
    });
    console.log(this.formularioRegistro);
  }

  registrar(): void {
    console.log(this.formularioRegistro);
    if (!this.formularioRegistro.valid) {
      console.log('Formulario no valido');
      return;
    }
    console.log('Formulario valido');
  }
}
