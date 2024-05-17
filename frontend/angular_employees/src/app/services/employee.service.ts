import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Employee } from '../models/employee.model';

@Injectable({
  providedIn: 'root'
})
export class EmployeeService {
  private apiUrl = 'http://127.0.0.1:8000/api';
  constructor(private http: HttpClient) {}
  createEmployee(employee: any): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}/employee`, employee);
  }

  getAll(): Observable<Employee[]> {
    return this.http.get<Employee[]>(`${this.apiUrl}/employee`);
  }

  getEmployee(id: number): Observable<Employee> {
    return this.http.get<Employee>(`${this.apiUrl}/employee/${id}`);
  }

  updateEmployee(id?: number, employee?: Employee): Observable<any> {
    return this.http.put(`${this.apiUrl}/employee/${id}`, employee);
  }

}
