import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { Respuesta } from '../estructuras/respuesta';

@Injectable({
  providedIn: 'root'
})
export class MentoriaService {

  httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/x-www-form-urlencoded' })
  };
  constructor(private http: HttpClient) { }

  getMentores(estado?) {
    estado = estado ? estado : 'T';
    const body = new HttpParams()
      .set('estado', estado);
    return this.http.post<Respuesta>(environment.base_api_url + 'mentoria/getMentores', body.toString(), this.httpOptions);
  }
}
