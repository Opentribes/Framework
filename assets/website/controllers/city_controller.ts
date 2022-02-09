import {Controller} from '@hotwired/stimulus';
import * as THREE from 'three'
import {GLTFLoader} from "three/examples/jsm/loaders/GLTFLoader";


export default class extends Controller {
    connect() {
        let width = this.element.clientWidth;
        let height = this.element.clientHeight;
        let aspectRatio = width / height;
        let loader = new GLTFLoader();
        let renderer = new THREE.WebGLRenderer({alpha: true});
        let size = 20;

        let camera:THREE.Camera;

        let scene = new THREE.Scene();

        renderer.setSize(width, height);
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.shadowMap.enabled = true;
        renderer.outputEncoding = THREE.sRGBEncoding;

        this.element.appendChild(renderer.domElement);


        let scenePath = this.element.getAttribute('data-scene');
        let centerPoint: THREE.Object3D;

        let hemi = new THREE.HemisphereLight(0xffffbb, 0x080820, 1);
        hemi.position.set(0, 100, 50);
        scene.add(hemi);

        loader.load(scenePath, (object) => {

                scene.add(object.scene);

                camera = object.cameras[0];

                camera.left = size * aspectRatio / -2;
                camera.right = size * aspectRatio /2;
                camera.top = size / 2;
                camera.bottom = size / -2;


                centerPoint = object.scene.getObjectByName("Empty");
                camera.lookAt(centerPoint.position);
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
