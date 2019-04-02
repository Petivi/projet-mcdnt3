import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Subscription } from 'rxjs';
import { List } from 'immutable'

import { AppService } from '../app.service';

import { Word, RecherchCreationPersonnage, Item, Character } from '../model/app.model';

import * as globals from '../../assets/data/globals';

/* donnée LOUIS pseudo: Mananga, Pteracuda serveur: Hyjal */

@Component({
    selector: 'creation-personnage-cpt',
    templateUrl: './creationPersonnage.component.html',
})
export class CreationPersonnageComponent implements OnInit {
    obsInit: Subscription;
    iconUrl: string = globals.blizzardIconUrl;
    ttItemGauche: any[] = [
        { id: 1, class: 4, inventoryType: 1, imgUrl: 'assets/img/inventoryslot_head.jpg', item: null },
        { id: 2, class: 4, inventoryType: 2, imgUrl: 'assets/img/inventoryslot_neck.jpg', item: null },
        { id: 3, class: 4, inventoryType: 3, imgUrl: 'assets/img/inventoryslot_shoulder.jpg', item: null },
        { id: 4, class: 4, inventoryType: 16, imgUrl: 'assets/img/inventoryslot_chest.jpg', item: null }, //cape mais pas encore l'image
        { id: 5, class: 4, inventoryType: 5, imgUrl: 'assets/img/inventoryslot_chest.jpg', item: null },
        /* { class: 4, inventoryType: 6, imgUrl: 'assets/img/inventoryslot_shirt.jpg', item: null }, */ //sait pas ce que c'est 
        /* { class: 4, inventoryType: 4, imgUrl: 'assets/img/inventoryslot_tabard.jpg', item: null }, */
        { id: 6, class: 4, inventoryType: 9, imgUrl: 'assets/img/inventoryslot_wrists.jpg', item: null },
        { id: 7, class: 4, inventoryType: 10, imgUrl: 'assets/img/inventoryslot_hands.jpg', item: null },
        { id: 8, class: 4, inventoryType: 6, imgUrl: 'assets/img/inventoryslot_waist.jpg', item: null },
    ];
    ttItemDroit: any[] = [
        { id: 9, class: 4, inventoryType: 7, imgUrl: 'assets/img/inventoryslot_legs.jpg', item: null },
        { id: 10, class: 4, inventoryType: 8, imgUrl: 'assets/img/inventoryslot_feet.jpg', item: null },
        { id: 11, class: 4, inventoryType: 11, imgUrl: 'assets/img/inventoryslot_finger.jpg', item: null },
        { id: 12, class: 4, inventoryType: 11, imgUrl: 'assets/img/inventoryslot_finger.jpg', item: null },
        { id: 13, class: 4, inventoryType: 12, imgUrl: 'assets/img/inventoryslot_trinket.jpg', item: null },
        { id: 14, class: 4, inventoryType: 12, imgUrl: 'assets/img/inventoryslot_trinket.jpg', item: null },
        { id: 15, class: 2, inventoryType: 13, imgUrl: 'assets/img/inventoryslot_mainhand.jpg', item: null },
        { id: 16, class: 4, inventoryType: 14, imgUrl: 'assets/img/inventoryslot_offhand.jpg', item: null }
    ];
    ttMatiere: any[] = [
        { id: 1, name: 'msg_cloth' },
        { id: 2, name: 'msg_cuir' },
        { id: 3, name: 'msg_maille' },
        { id: 4, name: 'msg_plate' },
        { id: 6, name: 'msg_bouclier' },
        { id: 0, name: 'msg_divers' }
    ];
    ttQuality: any[] = [
        { id: 0, name: 'msg_qualityPoor' },
        { id: 1, name: 'msg_qualityCommon' },
        { id: 2, name: 'msg_qualityUncommon' },
        { id: 3, name: 'msg_qualityRare' },
        { id: 4, name: 'msg_qualityEpic' },
        { id: 5, name: 'msg_qualityLegendary' },
        { id: 6, name: 'msg_qualityArtifact' },
        { id: 7, name: 'msg_qualityHeirloom' },
        { id: 8, name: 'msg_qualityWowtoken' },
    ];
    recherche: RecherchCreationPersonnage = { quality: -1, lvlMin: 0, lvlMax: 110, matiere: 2, name: '', class: null, inventoryType: null };
    displayCharacter: boolean = false;
    displayChoixItem: boolean = false;
    userConnected: any;
    displayItemDetailListe: boolean = false;
    displayItemDetailPerso: boolean = false;
    ttBonusStats: any[] = [];
    selectedItem: Item;
    oldItem: Item;
    selectedSlot: any;
    libelleAttack: string;
    ttItem: Item[] = [];
    gridData: List<any> = List([]);
    inventoryType: number = 1;
    ttClasseItem: any[] = [];
    words: Word[] = [];
    tabClasses: any[];
    tabRaces: any[];
    character: Character = new Character({});

    constructor(private _appService: AppService, private _activatedRoute: ActivatedRoute, private _router: Router) { }

    ngOnInit() {
        let ttPath = window.location.href.split('/');
        let lastPath = ttPath[ttPath.length - 1];
        if (lastPath !== 'creationPersonnage') {
            this._appService.post('action/api-blizzard/getOneCharacter.php', { session_token: this._appService.getToken(), character_id: lastPath }).then(res => {
                this.character = res[0]; //TODO: faire en sorte que le charactère créé soit le mmême que celui renvoyé par ce fichier php 
            });
        }
        this.userConnected = localStorage.getItem('userConnected');
        this.ttBonusStats = globals.bonusStats.map(bs => {
            if (this._appService.getLangue() === 'fr') {
                return { id: bs.id, libelle: bs.nameFr }
            } else {
                return { id: bs.id, libelle: bs.nameEn }
            }
        });
        this.obsInit = this._activatedRoute.data.subscribe(res => {
            this.words = res.resolver.words;
            this.ttClasseItem = res.resolver.classesItem ? res.resolver.classesItem : [];
            this.tabRaces = res.resolver.races;
            this.tabClasses = res.resolver.classes;
            if (this.tabRaces && this.tabClasses) {
                this.character.race_id = this.tabRaces[0].id;
                this.character.class_id = this.tabClasses[0].id;
                this.setCharacter();
            }
        });
    }

    ngOnDestroy() {
        this.obsInit.unsubscribe();
    }

    setGridData() {
        if (this.ttItem) {
            this.gridData = List(this.ttItem);
        }
    }

    setCharacter() {
        let statId = globals.statsClass.find(sc => sc.class == this.character.class_id) ? globals.statsClass.find(sc => sc.class == this.character.class_id).stat_id : null;
        this.libelleAttack = statId ? this.ttBonusStats.find(bs => bs.id === statId).libelle : undefined;
    }

    resetCharac() {
        this.character = new Character({});
        if (this.tabRaces && this.tabClasses) {
            this.character.race_id = this.tabRaces[0].id;
            this.character.class_id = this.tabClasses[0].id;
            this.setCharacter();
        }
        this.ttItemDroit.forEach(id => {
            id.item = null;
        });
        this.ttItemGauche.forEach(ig => {
            ig.item = null;
        });
        this.displayCharacter = false;
    }

    setRecherche(itemSlot) {
        if (itemSlot.item) {
            this.oldItem = itemSlot.item;
        } else {
            this.oldItem = null;
        }
        this.selectedSlot = itemSlot.id;
        this.recherche.class = itemSlot.class;
        this.recherche.inventoryType = itemSlot.inventoryType;
        this.getTtItem().then(res => {
            this.displayChoixItem = !this.displayChoixItem;
        });
    }

    getTtItem() {
        return new Promise((resolve, reject) => {
            this._appService.post('action/api-blizzard/getItemsInfo.php', {
                class: this.recherche.class,
                subClass: this.recherche.matiere,
                quality: this.recherche.quality,
                inventory_type: this.recherche.inventoryType,
                required_level_min: this.recherche.lvlMin,
                required_level_max: this.recherche.lvlMax,
                race_id: this.character.race_id,
                class_id: this.character.class_id,
                lang: this._appService.getLangue()
            }).then(res => {
                this.ttItem = res.response;
                this.ttItem.forEach(item => {
                    item.qualityName = this.ttQuality.find(qual => qual.id === parseInt(item.item_quality)) ? this.ttQuality.find(qual => qual.id == item.item_quality).name : '';
                });
                this.setGridData();
                resolve(true);
            }).catch(err => {
                reject(err);
            });
        });
    }

    getItemInfo(item: Item): Promise<Item> {
        return new Promise((resolve, reject) => {
            if (!item.id) {
                this._appService.getBlizzard('item/' + item.item_id).then(res => {
                    res.bonusStats.forEach(bonus => {
                        let bonusLarge = globals.bonusStats.find(bs => bs.id === bonus.stat);
                        if (bonusLarge) {
                            bonus.statLibelle = this._appService.getLangue() === 'fr' ? bonusLarge.nameFr : bonusLarge.nameEn;
                        }
                    });
                    let newItem: Item = this.ttItem.find(item => item.item_id === res.id);
                    newItem = { ...newItem, ...res };
                    this.ttItem.splice(this.ttItem.findIndex(item => item.item_id === newItem.id), 1, newItem);
                    this.setGridData();
                    resolve(newItem);
                    this.selectedItem = newItem;
                });
            } else {
                resolve(item);
            }
        });
    }

    showItemDetail(item: Item = null, itemSurPerso: boolean = false) {
        if (itemSurPerso) {
            if (item) {
                this.selectedItem = item;
                this.displayItemDetailPerso = true;
            } else {
                this.selectedItem = null;
                this.displayItemDetailPerso = false;
            }
        } else {
            if (item) {
                if (!this.selectedItem || item.id !== this.selectedItem.id) {
                    this.getItemInfo(item).then(res => {
                        this.selectedItem = res;
                        this.displayItemDetailListe = true;
                    });
                }
            } else {
                this.displayItemDetailListe = false;
                this.selectedItem = null;
            }
        }
    }

    selectItem(item: Item) {
        if (this.oldItem) {
            this.setStats(true);
        }
        if (this.selectedItem.id && item.item_id === this.selectedItem.id) {
            this.selectedItem.itemSlotId = this.selectedSlot;
            this.displayChoixItem = false;
            let itemD = this.ttItemDroit.find(itemDroit => itemDroit.id === this.selectedSlot);
            let itemG = this.ttItemGauche.find(itemGauche => itemGauche.id === this.selectedSlot);
            if (itemD) {
                this.selectedItem.imgUrl = globals.blizzardIconUrl + this.selectedItem.icon + '.jpg';
                itemD.item = this.selectedItem;
            } else if (itemG) {
                this.selectedItem.imgUrl = globals.blizzardIconUrl + this.selectedItem.icon + '.jpg';
                itemG.item = this.selectedItem;
            }
            switch (this.selectedItem.itemSlotId) {
                case 1:
                    this.character.head = {id: item.item_id, icon: item.icon};
                    break;
                case 2:
                    this.character.neck = {id: item.item_id, icon: item.icon};
                    break;
                case 3:
                    this.character.shoulder = {id: item.item_id, icon: item.icon};
                    break;
                case 4:
                    this.character.back = {id: item.item_id, icon: item.icon};
                    break;
                case 5:
                    this.character.chest = {id: item.item_id, icon: item.icon};
                    break;
                case 6:
                    this.character.wrist = {id: item.item_id, icon: item.icon};
                    break;
                case 7:
                    this.character.hands = {id: item.item_id, icon: item.icon};
                    break;
                case 8:
                    this.character.waist = {id: item.item_id, icon: item.icon};
                    break;
                case 9:
                    this.character.legs = {id: item.item_id, icon: item.icon};
                    break;
                case 10:
                    this.character.feet = {id: item.item_id, icon: item.icon};
                    break;
                case 11:
                    this.character.finger1 = {id: item.item_id, icon: item.icon};
                    break;
                case 12:
                    this.character.finger2 = {id: item.item_id, icon: item.icon};
                    break;
                case 13:
                    this.character.trinket1 = {id: item.item_id, icon: item.icon};
                    break;
                case 14:
                    this.character.trinket2 = {id: item.item_id, icon: item.icon};
                    break;
                case 15:
                    this.character.main_hand = {id: item.item_id, icon: item.icon};
                    break;
                case 16:
                    this.character.off_hand = {id: item.item_id, icon: item.icon};
                    break;
            }
            this.setCharactereStats();
        }
        this.selectedItem = null;
    }

    setCharactereStats() {
        this.character.attack = this.character.attack ? this.character.attack : 0;
        this.character.armour = this.character.armour ? this.character.armour : 0;
        this.character.stamina = this.character.stamina ? this.character.stamina : 0;
        this.character.health = this.character.health ? this.character.health : 0;
        this.character.critical_strike = this.character.critical_strike ? this.character.critical_strike : 0;
        this.character.haste = this.character.haste ? this.character.haste : 0;
        this.character.mastery = this.character.mastery ? this.character.mastery : 0;
        this.character.versatility = this.character.versatility ? this.character.versatility : 0;
        this.setStats();
    }

    setStats(oldItem: boolean = false) {
        if (this.findBonusStat(4)) {
            if (this.character.class_id == 1 || this.character.class_id == 2 || this.character.class_id == 6 || this.character.class_id == 10) {
                if (oldItem) {
                    this.character.attack -= this.findBonusStat(4, true).amount;
                } else {
                    this.character.attack += this.findBonusStat(4).amount;
                }
            }
        }
        if (this.findBonusStat(3)) {
            if (this.character.class_id == 7 || this.character.class_id == 4 || this.character.class_id == 3 || this.character.class_id == 10 || this.character.class_id == 11 || this.character.class_id == 12) {
                if (oldItem) {
                    this.character.attack -= this.findBonusStat(3, true).amount;
                } else {
                    this.character.attack += this.findBonusStat(3).amount;
                }
            }
        }
        if (this.findBonusStat(5)) {
            if (this.character.class_id == 7 || this.character.class_id == 5 || this.character.class_id == 2 || this.character.class_id == 8 || this.character.class_id == 9 || this.character.class_id == 10 || this.character.class_id == 11) {
                if (oldItem) {
                    this.character.attack -= this.findBonusStat(5, true).amount;
                } else {
                    this.character.attack += this.findBonusStat(5).amount;
                }
            }
        }
        if (this.findBonusStat(73)) {
            if (this.character.class_id == 7 || this.character.class_id == 2 || this.character.class_id == 4 || this.character.class_id == 3 || this.character.class_id == 5 || this.character.class_id == 8 || this.character.class_id == 9 || this.character.class_id == 10 || this.character.class_id == 11 || this.character.class_id == 12) {
                if (oldItem) {
                    this.character.attack -= this.findBonusStat(73, true).amount;
                } else {
                    this.character.attack += this.findBonusStat(73).amount;
                }
            }
        }
        if (this.findBonusStat(74)) {
            if (this.character.class_id == 1 || this.character.class_id == 7 || this.character.class_id == 2 || this.character.class_id == 5 || this.character.class_id == 6 || this.character.class_id == 8 || this.character.class_id == 9 || this.character.class_id == 10 || this.character.class_id == 11) {
                if (oldItem) {
                    this.character.attack -= this.findBonusStat(74, true).amount;
                } else {
                    this.character.attack += this.findBonusStat(74).amount;
                }
            }
        }
        if (oldItem) {
            this.character.attack -= this.findBonusStat(75, true) ? this.findBonusStat(75, true).amount : 0;
            this.character.attack -= this.findBonusStat(71, true) ? this.findBonusStat(71, true).amount : 0;
            this.character.versatility -= this.findBonusStat(67, true) ? this.findBonusStat(67, true).amount : 0;
            this.character.versatility -= this.findBonusStat(40, true) ? this.findBonusStat(40, true).amount : 0;
            this.character.armour -= this.oldItem.armor ? this.oldItem.armor : 0;
            this.character.mastery -= this.findBonusStat(49, true) ? this.findBonusStat(49, true).amount : 0;
            this.character.haste -= this.findBonusStat(36, true) ? this.findBonusStat(36, true).amount : 0;
            this.character.critical_strike -= this.findBonusStat(32, true) ? this.findBonusStat(32, true).amount : 0;
        } else {
            this.character.attack += this.findBonusStat(75) ? this.findBonusStat(75).amount : 0;
            this.character.attack += this.findBonusStat(71) ? this.findBonusStat(71).amount : 0;
            this.character.versatility += this.findBonusStat(67) ? this.findBonusStat(67).amount : 0;
            this.character.versatility += this.findBonusStat(40) ? this.findBonusStat(40).amount : 0;
            this.character.armour += this.selectedItem.armor ? this.selectedItem.armor : 0;
            this.character.mastery += this.findBonusStat(49) ? this.findBonusStat(49).amount : 0;
            this.character.haste += this.findBonusStat(36) ? this.findBonusStat(36).amount : 0;
            this.character.critical_strike += this.findBonusStat(32) ? this.findBonusStat(32).amount : 0;
        }
        if (this.findBonusStat(7)) {
            if (oldItem) {
                this.character.stamina -= this.findBonusStat(7, true).amount;
            } else {
                this.character.stamina += this.findBonusStat(7).amount;
            }
            this.character.health = this.character.stamina * 10;
        }
    }

    findBonusStat(bonnusId, oldItem: boolean = false) {
        if (oldItem) {
            return this.oldItem.bonusStats.find(bs => bs.stat === bonnusId);
        } else {
            return this.selectedItem.bonusStats.find(bs => bs.stat === bonnusId);
        }
    }

    saveCharac() {
        this._appService.post('action/api-blizzard/addNewCharacter.php',
            { session_token: JSON.parse(localStorage.getItem("userConnected")).session_token, character: this.character }).then(res => {
                if (res.response) {
                    this._router.navigate(['/accueil']);
                }
            });
    }
}

