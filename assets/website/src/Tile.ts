import {Object3D} from "three";

export default interface Tile {
    id: string;
    fileName: string;
    object?: Object3D;
};