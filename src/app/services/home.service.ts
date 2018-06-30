import { Injectable } from '@angular/core';

import { Http, Response, Headers, RequestOptions } from '@angular/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';

import { Globals } from '../globals';

@Injectable({
	providedIn: 'root'
})
export class HomeService {

	constructor( private globals: Globals, private http: Http) { }

	private BASE_URL: string = this.globals.baseURL;

	showTrackingDetail(value:any){
		
		let url: string = this.BASE_URL + 'home/showDetail';
		
		return this.http.post(url, JSON.stringify(value.param[0])).pipe(map(	
			res => res.json()
		));
	}

	getPosts(){
		let url: string = this.BASE_URL + 'home/trackingList';	

		return this.http.get(url).pipe(map(res =>res.json()));
	}

	getDirection(value:any){
		let url: string = this.BASE_URL + 'home/getDirection';
		
		return this.http.post(url, JSON.stringify(value.param[0])).pipe(map(	
			res => res.json()
		));
	}
	onMove(value: any){
		let url: string = this.BASE_URL + 'home/onMove';
		
		return this.http.post(url, JSON.stringify(value.param[0])).pipe(map(	
			res => res.json()
		));
	}
}
