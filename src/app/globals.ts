import { Injectable } from '@angular/core';
import { environment } from '../environments/environment';

@Injectable()
export class Globals {
	baseURL: string = environment.baseURL;
	  
}