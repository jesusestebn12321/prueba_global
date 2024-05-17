import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = 'http://127.0.0.1:8000/api';

  constructor(private http: HttpClient, private router: Router) {}

  login(email: string, password: string): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}/login`, { email, password })
      .pipe(
        map(response => {
          if (response.message === 'Logged in successfully') {
            localStorage.setItem('user', JSON.stringify(response.user));
          }
          return response;
        })
      );
  }

  logout(): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}/logout`, {})
      .pipe(
        map(response => {
          localStorage.removeItem('user');
          this.router.navigate(['/login']);
          return response;
        })
      );
  }

  register(name: string, email: string, password: string, password_confirmation: string): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}/register`, { name, email, password, password_confirmation });
  }

  isLoggedIn(): boolean {
    return localStorage.getItem('user') !== null;
  }
}
