import {Controller} from '@hotwired/stimulus';
import * as THREE from 'three'

import {GLTFLoader} from "three/examples/jsm/loaders/GLTFLoader";
import {Camera} from "three";


export default class extends Controller {
    connect() {
        let width = window.innerWidth;
        let height =window.innerHeight;
        let aspectRatio = width/height;
        let loader = new GLTFLoader();
        let renderer = new THREE.WebGLRenderer({alpha: true});
        let camera:THREE.OrthographicCamera;
        let scene = new THREE.Scene();


        renderer.setSize(width,height);
        renderer.setPixelRatio(window.devicePixelRatio);

        this.element.appendChild(renderer.domElement);


        let scenePath = this.element.getAttribute('data-scene');
        loader.load(scenePath, (object) => {

                scene.add(object.scene);

                camera = object.cameras[0];
                camera.aspect = aspectRatio;
                camera.updateProjectionMatrix();

                console.log(camera,object);
                renderer.render(scene, camera);

            }, (xhr) => {
                console.log((xhr.loaded / xhr.total) * 100 + '% loaded')
            },
            (error) => {
                console.log(error)
            });

    }
}
