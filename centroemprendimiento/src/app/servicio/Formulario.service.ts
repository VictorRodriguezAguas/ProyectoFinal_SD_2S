import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { Respuesta } from '../estructuras/respuesta';
import { Formulario } from '../interfaces/Formulario';

@Injectable({
  providedIn: 'root'
})
export class FormularioService {

  constructor(private http: HttpClient) { }

  httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/x-www-form-urlencoded' })
  };

  getFormulario(id_formulario) {
    const body = new HttpParams()
      .set('id_formulario', id_formulario);
    return this.http.post<Respuesta>(environment.base_api_url + 'formulario/getFormulario', body.toString(), this.httpOptions);
  }

  getRegistro(id_registro) {
    const body = new HttpParams()
      .set('id_registro', id_registro);
    return this.http.post<Respuesta>(environment.base_api_url + 'formulario/getRegistro', body.toString(), this.httpOptions);
  }

  grabarRegistroFormulario(registro:Formulario) {
    const body = new HttpParams()
      .set('datos', JSON.stringify(registro));
    return this.http.post<Respuesta>(environment.base_api_url + 'formulario/grabarRegistro', body.toString(), this.httpOptions);
  }
}
