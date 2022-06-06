import {Controller} from '@hotwired/stimulus';
import * as THREE from 'three'
import {GLTFLoader} from "three/examples/jsm/loaders/GLTFLoader";
import TileRepository from "../src/TileRepository";

import {Vector2, Vector3} from "three";
import {degToRad} from "three/src/math/MathUtils";
import * as TWEEN from "@tweenjs/tween.js";

export default class extends Controller {
    async connect() {
        const container: Element = this.element;
        const width = container.clientWidth;
        const height = container.clientHeight;
        const aspectRatio = width / height;

        const mapDataJson = JSON.parse(container.getAttribute('data-map'));
        const tilePath = container.getAttribute('data-tilePath');
        const halfPi = Math.PI / 2;
        const camera = new THREE.PerspectiveCamera(45, aspectRatio, 1, 10000);
        const scene = new THREE.Scene();

        const renderer = new THREE.WebGLRenderer({antialias: true, alpha: true});



        const loader = new GLTFLoader();

        const tileRepository = new TileRepository(tilePath, loader);


        const tileList = await tileRepository.getTileList();

        const hemi = new THREE.HemisphereLight(0xffffbb, 0x080820, 1);

        const axesHelper = new THREE.AxesHelper(5);
        scene.rotateZ(degToRad(-90));

        scene.position.set(0, 0, 0);


        scene.add(axesHelper);



        camera.lookAt(new Vector3(0, 0, 0));
        camera.position.set(0, -20, 20);
        camera.rotation.x = degToRad(33);

        hemi.position.set(0, 0, 100);

        scene.add(hemi);

        mapDataJson.layers.background.forEach(function (tileJson) {
            const currentTile = tileList.get(tileJson.data);
            if (currentTile === undefined) {
                return;
            }
            const meshData = currentTile.object.clone(true);

            meshData.uuid = tileJson.id;
            meshData.rotateX(halfPi);
            meshData.position.set(tileJson.location.x, tileJson.location.y, 0);
            meshData.scale.set(0.5, 0.5, 0.5);
            scene.add(meshData);
        });

        container.appendChild(renderer.domElement);
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setSize(width, height);

        const moveSpeed = 2;


        document.addEventListener('keypress', function (e) {

            const position = {
                x: camera.position.x,
                y: camera.position.y
            };
            const vector = new Vector2(0, 0);

            if (e.key === 'd') {
                vector.x = moveSpeed;
            }
            if (e.key === 'a') {
                vector.x = -moveSpeed;
            }
            if (e.key === 'w') {
                vector.y = moveSpeed;
            }
            if (e.key === 's') {
                vector.y = -moveSpeed;
            }

            const tween = new TWEEN.Tween(position)
                .to({x: position.x + vector.x, y: position.y + vector.y}, 100)
                .easing(TWEEN.Easing.Quadratic.Out)
                .onUpdate(function () {
                    camera.position.set(position.x, position.y, camera.position.z);

                })
                .start();

        });


        animate(0);

        function animate(time) {

            requestAnimationFrame(animate);
            TWEEN.update(time);
            renderer.render(scene, camera);
        }

    }
}