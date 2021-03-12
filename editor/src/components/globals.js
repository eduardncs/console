import React, { Suspense } from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';
import Utils from './utils';
import { changeSrc } from './sandbox';
import { Spinner } from 'react-bootstrap';
import Swal from 'sweetalert2';
import loadable from '@loadable/component'
import { domain, postMan } from './backend';
import { User , Project } from './data';


const tooltips={valInternal:[],set val(t){this.valInternal=t},get val(){return this.valInternal},clear:()=>{tooltips.val=[]}};
const toolboxes={valInternal:[],set val(l){this.valInternal=l},get val(){return this.valInternal},clear:()=>{tooltips.val=[]}};
const toast=async(t)=>{Swal.mixin({toast:!0,position:"top-end",showConfirmButton:!1,timer:3e3}).fire({icon:"success",title:t})};
const etoast=async(o)=>{Swal.mixin({toast:!0,position:"top-end",showConfirmButton:!1,timer:3e3}).fire({icon:"error",title:o})};
const wtoast=async(t)=>{Swal.mixin({toast:!0,position:"top-end",showConfirmButton:!1,timer:3e3}).fire({icon:"warning",title:t})};
const itoast=async(t)=>{Swal.mixin({toast:!0,position:"top-end",showConfirmButton:!1,timer:3e3}).fire({icon:"info",title:t})};
const page = {
    pageInternal: null,
    currentPage: "home",
    set val(val) {
        Array.isArray(val) ? this.pageInternal = val[0] : this.pageInternal = val;
        this.pageListener(val);
    },
    set current(val) {
        this.currentPage = val;
        $("#currentpage").text(Capitalize(val))
    },
    get val() {
        return this.pageInternal;
    },
    get current() {
        return this.currentPage;
    }
}

const Overlay = () => {
    return(
        <div className="position-absolute h-100 w-100 row text-center align-items-center justify-content-center bg-white" id="overlay"
        style={{
            margin:"0",
            padding:"0",
            zIndex:"9999"
        }}>
            <div className="col-6 mx-auto text-center">
            <Spinner animation="border" role="status">
                <span className="sr-only">Loading...</span>
            </Spinner>
                <h2 className="mt-2">Editor is loading all required assets, please wait <span>.</span><span>.</span><span>.</span></h2>
            </div>
        </div>
    )
}

const changePerspective = (perspective) =>{
    perspective === "mobile" ? $(".sandbox").animate({ width: '350px', height: '648px' }, 500, 'linear')
    : $(".sandbox").animate({ width: '100%', height: '100%' }, 500, 'linear');
}

/**
 * Return an unique id
 * @return string id
 */
const ID = _ => {
    return '_' + Math.random().toString(36).substr(2, 9);
};
/**
 * Equivalent to ucfrist in php
 * @param {string} s Capitalize first letter from the string
 */
const Capitalize = (s) => {
    if (typeof s !== 'string') return ''
    return s.charAt(0).toUpperCase() + s.slice(1)
}
/**
 * 
 */
const loadExtModule = async (module) => {
    const ReactModule = loadable(props => import(`./${module}`))
    ReactDOM.render(
        <Suspense fallback={<div>Loading...</div>}>
            <ReactModule/>
        </Suspense>
        , document.querySelector("#externalModuleContainer")
    );
}

export {
    changePerspective,
    Overlay,
    Capitalize,
    tooltips,
    toolboxes,
    ID,
    toast,
    etoast,
    wtoast,
    itoast,
    page,
    loadExtModule
}