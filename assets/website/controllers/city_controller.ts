import {Controller} from '@hotwired/stimulus';
import * as THREE from 'three'
import {GLTFLoader} from "three/examples/jsm/loaders/GLTFLoader";



export default class extends Controller {
    connect() {
        let width = this.element.clientWidth;
        let height =this.element.clientHeight;
        let aspectRatio = width/height;
        let loader = new GLTFLoader();
        let renderer = new THREE.WebGLRenderer({alpha: true});
        let camera:THREE.Camera;
        let scene = new THREE.Scene();

        renderer.setSize(width,height);
        renderer.setPixelRatio(window.devicePixelRatio);

        this.element.appendChild(renderer.domElement);


        let scenePath = this.element.getAttribute('data-scene');
        loader.load(scenePath, (object) => {

                scene.add(object.scene);

                camera = object.cameras[0];
                console.log(camera,object);
                camera.aspect = aspectRatio;
                camera.updateProjectionMatrix();
                renderer.render(scene, camera);

            }, (xhr) => {
                console.log((xhr.loaded / xhr.total) * 100 + '% loaded')
            },
            (error) => {
                console.log(error)
            });

    }
}
