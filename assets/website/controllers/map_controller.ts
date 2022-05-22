import {Controller} from '@hotwired/stimulus';
import * as THREE from 'three'
import {GLTFLoader} from "three/examples/jsm/loaders/GLTFLoader";
import TileRepository from "../src/TileRepository";


export default class extends Controller {
    connect() {
        const container: Element = this.element;
        const width = container.clientWidth;
        const height = container.clientHeight;
        const aspectRatio = width / height;
        const mapDataJson = JSON.parse(container.getAttribute('data-map'));
        const tilePath = container.getAttribute('data-tilePath');

        const camera = new THREE.PerspectiveCamera(30, aspectRatio, 1, 5000);
        const scene = new THREE.Scene();
        const renderer = new THREE.WebGLRenderer({antialias: true, alpha: true});
        const loader = new GLTFLoader();

        const tileRepository = new TileRepository(tilePath, loader);


        const tileList = tileRepository.getTileList();
        tileList.then(function (tileList) {

            camera.lookAt(scene.position);

            mapDataJson.layers.background.forEach(function (tileJson) {
                const currentTile = tileList.get(tileJson.data);

                currentTile.object.uuid = tileJson.id;
                currentTile.object.position.x = tileJson.location.x;
                currentTile.object.position.y = tileJson.location.y;

                scene.add(currentTile.object);
            });
            console.log(scene);


            renderer.setPixelRatio(window.devicePixelRatio);
            renderer.setSize(width, height);

            container.appendChild(renderer.domElement);
            renderer.render(scene, camera);

        });


    }
}