import { NgModule, ModuleWithProviders } from "@angular/core";
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router';

import { HomeComponent } from './components/home/home.component';

const appRoutes: Routes = [
	{path: '', redirectTo: '/home', pathMatch: 'full'},
	{path: 'home', component: HomeComponent},
];

@NgModule({
	imports: [
		CommonModule,
		RouterModule.forRoot(appRoutes)
	],
	exports: [RouterModule],
	declarations: []
})
export class AppRouting { }
