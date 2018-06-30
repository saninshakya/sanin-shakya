import { Component, OnInit } from '@angular/core';

import { Observable } from 'rxjs';
//Import forms modules
import { FormGroup, FormControl,FormBuilder, Validators} from '@angular/forms';
import {  Http, Response, Headers } from '@angular/http';

import { HomeService } from '../../services/home.service';

import { trigger, state, style, animate, transition } from '@angular/animations';

@Component({
	selector: 'app-home',
	templateUrl: './home.component.html',
	styleUrls: ['./home.component.css']
})

export class HomeComponent implements OnInit {

	constructor(private homeservice: HomeService, private http: Http) { }
	public status: any;
	public shouldShow = true;

	IsHidden= true;

	public rotationAmount: any;
	public direction: string;
	public currentLocation: any;
	public newPoint: any;
	public isVisible: boolean = true;
	public disable: boolean = true;
	public play: boolean = false;

	public firstMsg: string = 'Press Play to start playing';
	public skills: any[] = [];
	private isButtonVisible = true;

	ngOnInit() {
		this.rotationAmount = 0;
		this.skills.push({ title: this.firstMsg });
	}


	onPlay(){
		if (this.play == false)
		{
			this.skills.push({title: 'Play button clicked'});
			this.skills.reverse();
			this.play = true;
			this.direction = 'East';
			this.isVisible = !this.isVisible;
		}	
		
	}
	
	OnMove(){
		let nodeList = document.querySelectorAll('.active');
		let els =Array.from(nodeList);
		this.currentLocation = els[0].className.split(' ')[0];

		let param = [{direction: this.direction, currentLocation: this.currentLocation}];
		this.homeservice.onMove({param}).subscribe((results) => {
			//remove old classname
			var el = document.getElementsByClassName(results.oldPoint);
			el[0].classList.remove("active");

			//add new classname
			var d = document.getElementsByClassName(results.newPoint);
			if (d.length !== 0)
			{
				d[0].className += ' active';
				this.skills.push({title: 'Drone is moved('+ results.newPoint+") from ("+ results.oldPoint+")"});
				this.skills.reverse();
			} else{
				alert("This step is not valid");
				el[0].className += ' active';
			}
			
			(<HTMLElement>document.querySelector('.active')).style.transform = 'rotate(' + this.rotationAmount + 'deg)';

		});
	}
	rotateImage(side: string) {
		let param = [{activeSide: side, direction: this.direction}];
		this.homeservice.getDirection({param}).subscribe((results) => {
			this.direction = results.direction

		});

		if (side === 'left') {
			this.rotationAmount = this.rotationAmount + -90;
			this.skills.push({title: 'Rotate 90 degree left'});
			this.skills.reverse();
		} else if(side === 'right') {
			this.rotationAmount = this.rotationAmount + 90;
			this.skills.push({title: 'Rotate 90 degree right'});
			this.skills.reverse();
		}
		(<HTMLElement>document.querySelector('.active')).style.transform = 'rotate(' + this.rotationAmount + 'deg)';
	}

}
