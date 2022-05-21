import {Mesh} from "three";

export default interface Tile {
    id: string;
    fileName: string;
    mesh?: Mesh;
};