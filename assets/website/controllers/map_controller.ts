import {Controller} from '@hotwired/stimulus';
import * as THREE from 'three'
import {GLTFLoader} from "three/examples/jsm/loaders/GLTFLoader";
import TileRepository from "../src/TileRepository";


export default class extends Controller {
    async connect() {
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


        const tileList = await tileRepository.getTileList();

        camera.lookAt(scene.position);


        mapDataJson.layers.background.forEach(function (tileJson) {
            const currentTile =tileList.get(tileJson.data);
            const meshData = currentTile.object.clone(true);

            meshData.uuid = tileJson.id;
            meshData.position.x = tileJson.location.x;
            meshData.position.y = tileJson.location.y;

           scene.add(meshData);
        });
        console.log(scene);


        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setSize(width, height);

        container.appendChild(renderer.domElement);
        renderer.render(scene, camera);


    }
}