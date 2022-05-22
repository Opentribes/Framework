import {Controller} from '@hotwired/stimulus';
import * as THREE from 'three'
import {GLTFLoader} from "three/examples/jsm/loaders/GLTFLoader";
import TileRepository from "../src/TileRepository";

import {OrbitControls} from "three/examples/jsm/controls/OrbitControls";


export default class extends Controller {
    async connect() {
        const container: Element = this.element;
        const width = container.clientWidth;
        const height = container.clientHeight;
        const aspectRatio = width / height;

        const mapDataJson = JSON.parse(container.getAttribute('data-map'));
        const tilePath = container.getAttribute('data-tilePath');
        THREE.Object3D.DefaultUp = new THREE.Vector3(0, 0, 1);
        const camera = new THREE.PerspectiveCamera(45, aspectRatio, 1, 10000);
        const scene = new THREE.Scene();
        const renderer = new THREE.WebGLRenderer({antialias: true, alpha: true});
        const controls = new OrbitControls(camera, renderer.domElement);


        const loader = new GLTFLoader();

        const tileRepository = new TileRepository(tilePath, loader);


        const tileList = await tileRepository.getTileList();

        const hemi = new THREE.HemisphereLight(0xffffbb, 0x080820, 1);


        controls.enableDamping = true; // an animation loop is required when either damping or auto-rotation are enabled
        controls.dampingFactor = 0.05;

        controls.minDistance = 10;
        controls.maxDistance = 30;

        camera.position.set(0, 10, -20);

        hemi.position.set(0, 20, 0);


        scene.add(hemi);



        mapDataJson.layers.background.forEach(function (tileJson) {
            const currentTile = tileList.get(tileJson.data);
            if (currentTile === undefined) {
                return;
            }
            const meshData = currentTile.object.clone(true);

            meshData.uuid = tileJson.id;
            meshData.position.x = tileJson.location.x * -1;
            meshData.position.z = tileJson.location.y * -1;
            meshData.scale.set(0.5, 0.5, 0.5);
            scene.add(meshData);
        });


        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setSize(width, height);

        container.appendChild(renderer.domElement);
        controls.update();


        animate();

        function animate() {

            requestAnimationFrame(animate);
            // required if controls.enableDamping or controls.autoRotate are set to true
            controls.update();
            renderer.render(scene, camera);
        }

    }
}