import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';

@Component({
  selector: 'app-add-asignaturas',
  templateUrl: './add-asignaturas.page.html',
  styleUrls: ['./add-asignaturas.page.scss'],
})
export class AddAsignaturasPage {
  nombre: string;
  horas: number;
  curso_id: number;

  constructor(private http: HttpClient, private router: Router) {}

  addAsignatura() {
    const asignatura = {
      nombre: this.nombre,
      horas: this.horas,
      curso_id: this.curso_id
    };

    this.http.post('http://3.218.6.79:8000/curso/add-asignatura', asignatura)
      .subscribe(
        response => {
          console.log('Asignatura added successfully', response);
          this.router.navigate(['/']);
        },
        error => {
          console.error('Error adding asignatura', error);
        }
      );
  }
}
