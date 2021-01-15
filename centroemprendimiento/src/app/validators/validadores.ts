import { Directive, ElementRef, forwardRef, Input, Renderer2, ViewChild } from '@angular/core';
import { AbstractControl, FormGroup, NgControl, NG_VALIDATORS, ValidationErrors, Validator, ValidatorFn } from '@angular/forms';
import { General } from 'src/app/estructuras/General';
import { NG_VALUE_ACCESSOR, DefaultValueAccessor } from '@angular/forms';

export const validarQueSeanIguales: ValidatorFn = (control: FormGroup): ValidationErrors | null => {
  const password = control.get('password');
  const confirmarPassword = control.get('confirmarPassword');

  return password.value === confirmarPassword.value ? null : { 'noSonIguales': true };
};

export function validarEdadMin(valor): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    var edad = General.getEdad(control.value);
    if (edad == null) {
      return null;
    }
    if (edad < valor) {
      return { 'validaredadmin': true };
    }
    return null;
  };
}

@Directive({
  selector: '[validaredadmin]',
  providers: [{ provide: NG_VALIDATORS, useExisting: validarEdadMinDirective, multi: true }]
})
export class validarEdadMinDirective implements Validator {
  @Input('validaredadmin') edadMinima: number;

  constructor(private renderer: Renderer2, private elementRef: ElementRef) { }

  validate(control: AbstractControl): { [key: string]: any } | null {
    return this.edadMinima ? validarEdadMin(this.edadMinima)(control) : null;
  }
}

@Directive({
  selector: '[msmvalidador]',
  providers: [{ provide: NG_VALIDATORS, useExisting: msmValidadorDirective, multi: true }]
})
export class msmValidadorDirective implements Validator {
  @Input('validaredadmin') edadMinima: number;

  constructor(private renderer: Renderer2, private elementRef: ElementRef) { }

  validate(control: AbstractControl): { [key: string]: any } | null {
    console.log('Ingresa a validator');
    let classVal = 'is-invalid';
    if (control.dirty) {
      this.renderer.removeClass(this.elementRef.nativeElement, 'is-invalid');
      this.renderer.removeClass(this.elementRef.nativeElement, 'is-valid');
    }
    if (control.errors) {
      if (control.errors.required) {
        if (control.dirty)
          this.renderer.addClass(this.elementRef.nativeElement, classVal);
        return { 'errorMessage': "Campo requerido" };
      }
      if (control.errors.validaredadmin) {
        if (control.dirty)
          this.renderer.addClass(this.elementRef.nativeElement, classVal);
        return { 'errorMessage': "La edad debe ser mayor o igual a " + this.edadMinima };
      }
    }
    classVal = 'is-valid';
    if (control.dirty)
      this.renderer.addClass(this.elementRef.nativeElement, classVal);
    return null;
  }

}


@Directive({
  selector: '[msmvalidador2]',
  host: {
    '(blur)': 'onBlur($event)'
  }
})
export class msmValidadorDirective2 extends DefaultValueAccessor {
  @Input('validaredadmin') edadMinima: number;

  constructor(private renderer: Renderer2, private elementRef: ElementRef, public formControl: NgControl) {
    super(renderer, elementRef, false);
  }

  onBlur(value: any): void {
    let control = this.formControl.control;
    let classVal = 'is-invalid';
    if (control.dirty) {
      this.renderer.removeClass(this.elementRef.nativeElement, 'is-invalid');
      this.renderer.removeClass(this.elementRef.nativeElement, 'is-valid');
    }
    if (control.errors) {
      if (control.errors.required) {
        if (control.dirty)
          this.renderer.addClass(this.elementRef.nativeElement, classVal);
        control.errors.errorMessage = "Campo requerido";
      }
      if (control.errors.validaredadmin) {
        if (control.dirty)
          this.renderer.addClass(this.elementRef.nativeElement, classVal);
        control.errors.errorMessage = "La edad debe ser mayor o igual a " + this.edadMinima;
      }
    }
    classVal = 'is-valid';
    if (control.dirty)
      this.renderer.addClass(this.elementRef.nativeElement, classVal);
  }

}