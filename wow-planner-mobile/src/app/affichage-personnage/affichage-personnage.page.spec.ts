import { CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AffichagePersonnagePage } from './affichage-personnage.page';

describe('AffichagePersonnagePage', () => {
  let component: AffichagePersonnagePage;
  let fixture: ComponentFixture<AffichagePersonnagePage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AffichagePersonnagePage ],
      schemas: [CUSTOM_ELEMENTS_SCHEMA],
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AffichagePersonnagePage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
