import {Loader, Scene} from "three/src/Three";
import Tile from "./Tile";

export default class GameMap {
    tileList: Map<string,Tile>;
    scene: Scene;
    constructor(tileList: Map<string,Tile>, scene: Scene) {
        this.scene = scene;
        this.tileList = tileList;
    }

    drawTiles(data){
        const tileList = this.tileList;
        const scene = this.scene;

        data.layers.background.forEach(function (tileJson) {
            const currentTile = tileList.get(tileJson.data);
            if (currentTile === undefined) {
                return;
            }
            const meshData = currentTile.object.clone(true);
            const tile = scene.getObjectByName(tileJson.id);

            if(tile !== undefined){
                return;
            }

            meshData.uuid = tileJson.id;
            meshData.name = tileJson.id;
            meshData.position.set(tileJson.location.x, 0, tileJson.location.y);
            meshData.scale.set(0.5, 0.5, 0.5);
            scene.add(meshData);
        });

    }
}