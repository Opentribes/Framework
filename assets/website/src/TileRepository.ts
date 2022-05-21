'use strict';
import Tile from "./Tile";
import {GLTFLoader} from "three/examples/jsm/loaders/GLTFLoader";
import {Loader} from "three/src/Three";

export default class TileRepository {
    tilePath: string;
    tileList: Map<string, Tile>;
    loader: Loader;

    constructor(tilePath: string, loader: Loader) {
        this.tilePath = tilePath;
        this.loader = loader;
        this._prepareTiles();
    }

    _prepareTiles() {
        this.tileList = new Map();
        this.tileList.set('0000', {id: '0000', fileName: 'gras'});
        this.tileList.set('0001', {id: '0001', fileName: 'sea'});
    }

    async getTileList() {
        let promises = [];
        const obj = this;
        this.tileList.forEach(function (tile) {
            promises.push({
                id: tile.id,
                promise: obj.loader.loadAsync(`${obj.tilePath}/${tile.fileName}.glb`)
            });
        });


        const tileData = await Promise.all(promises.map(({promise}) => promise));

        tileData.forEach(function(data,index){
            let currentTileIndex = promises[index].id;
            let currentTile = obj.tileList.get(currentTileIndex);
            currentTile.mesh = data.scene.children[0];
            obj.tileList.set(currentTileIndex,currentTile);
        });
        return this.tileList;
    }

    async _getTilePromises() {

    }

}