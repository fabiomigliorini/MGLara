/*
 * Slim v3.3.1 - Image Cropping Made Easy
 * Copyright (c) 2016 Rik Schennink - http://slimimagecropper.com
 */
import { Component, Input, ElementRef, OnInit } from "@angular/core";
import Slim from './slim.module.js';

@Component({
	selector: 'slim',
	template: '<ng-content></ng-content>'
})

export default class {

	@Input() options: Object;

	constructor(private el:ElementRef) {}

	ngOnInit():any {
		this.instance = Slim.create(this.el.nativeElement, this.options);
	}
};