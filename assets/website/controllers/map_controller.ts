import {Controller} from '@hotwired/stimulus';
import * as THREE from 'three'
import {GLTFLoader,GLTF} from "three/examples/jsm/loaders/GLTFLoader";
import {OrthographicCamera} from "three/src/cameras/OrthographicCamera";
import {Vector3} from "three";
import Stats from "three/examples/jsm/libs/stats.module";

export default class extends Controller {
    connect() {
        const container: Element = this.element;
        const width = container.clientWidth;
        const height = container.clientHeight;
        const aspectRatio = width / height;
        const mapDataJson = container.getAttribute('data-map');
        const tilePath = container.getAttribute('data-tilePath');
        const camera = new THREE.PerspectiveCamera(30, aspectRatio, 1, 5000);
        const scene = new THREE.Scene();
        const renderer = new THREE.WebGLRenderer({antialias: true, alpha: true});
        const loader = new GLTFLoader();

        let tileList = [
            {
                fileName:'gras',
                mapName: '0000',
                data:{}
            }
        ];


        tileList.forEach(function(tileData){
            let promise = loader.loadAsync(`${tilePath}/${tileData.fileName}.glb`);
            promise.then(function (data){
                tileData.data = data;
            });
        });
        console.log(tileList);

        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setSize(width, height);
        container.appendChild(renderer.domElement);
        renderer.render(scene, camera);


    }
}