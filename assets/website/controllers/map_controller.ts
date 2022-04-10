import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    connect() {


        const mapDataJson = this.element.getAttribute('data-map');

        const map = JSON.parse(mapDataJson);
        console.log(map);
    }
}