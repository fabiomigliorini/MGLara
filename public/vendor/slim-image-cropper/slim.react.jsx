/*
 * Slim v3.3.1 - Image Cropping Made Easy
 * Copyright (c) 2016 Rik Schennink - http://slimimagecropper.com
 */
// Necessary React Modules
import React from 'react';
import ReactDOM from 'react-dom';
import _Slim from './slim.module.js';

// React Component
export default class Slim extends React.Component {

	componentDidMount() {
		this.slim = _Slim ? _Slim.create(ReactDOM.findDOMNode(this), this.props) : null;
	}

	render() {
		return <div className="slim">{ this.props.children }</div>
	}

}