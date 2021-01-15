import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { Respuesta } from '../estructuras/respuesta';

@Injectable({
  providedIn: 'root'
})
export class PanelforosService {

  constructor(private http: HttpClient) {

    
   }
   

   httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/x-www-form-urlencoded' })
  };
  getForos() {
    const body = new HttpParams();
    return this.http.post<Respuesta>(environment.base_api_url + 'foro/getForos', body.toString(), this.httpOptions);
  }
}
