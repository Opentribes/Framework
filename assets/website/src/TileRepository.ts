import Tile from "./Tile";
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
        this.tileList.set('0001', {id: '0001', fileName: 'gras'});
        this.tileList.set('0002', {id: '0002', fileName: 'sea'});
        this.tileList.set('0003', {id: '0003', fileName: 'mountain'});
    }

    async getTileList() {
        let promises = [];
        const scope = this;
        this.tileList.forEach(function (tile) {
            promises.push({
                id: tile.id,
                promise: scope.loader.loadAsync(`${scope.tilePath}/${tile.fileName}.glb`)
            });
        });


        const tileData = await Promise.all(promises.map(({promise}) => promise));

        tileData.forEach((data, index) => {
            let currentTileIndex = promises[index].id;
            let currentTile = scope.tileList.get(currentTileIndex);
            data.scene.parent = null;
            currentTile.object = data.scene;
            scope.tileList.set(currentTileIndex, currentTile);
        });

        return this.tileList;
    }
}