import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class ApiGoogleService {
  constructor(private httpClient: HttpClient) {}

  getGeocoding(address):Promise<any>{
    const API_KEY = 'AIzaSyAoF7F4-cI0u75-hCo9N5KDDgUPqG_ph9Q';
    const query = address.replace(/ /gi,'+');

    let url = 'https://maps.googleapis.com/maps/api/geocode/json?address='+query+'&key='+API_KEY
    return this.httpClient.get<any>(url).toPromise();
  }

  getReverseGeocoding(pos):Promise<any>{
    const API_KEY = 'AIzaSyAoF7F4-cI0u75-hCo9N5KDDgUPqG_ph9Q';
    const query = pos.lat + ',' + pos.lng;

    let url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='+query+'&key='+API_KEY
    return this.httpClient.get<any>(url).toPromise();
  }
}
