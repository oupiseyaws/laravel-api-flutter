import 'package:flutter/material.dart';

import '../services/api.dart';

class AuthProvider extends ChangeNotifier {
  bool isAuthenticated = false;
  late String token;
  ApiService apiService = ApiService('');

  AuthProvider();

  Future<void> register(String name, String email, String password,
      String passwordConfirmation, String deviceName) async {
    token = await apiService.register(
        name, email, password, passwordConfirmation, deviceName);
    isAuthenticated = true;
    notifyListeners();
  }

  Future<void> login(String email, String password, String deviceName) async {
    token = await apiService.login(email, password, deviceName);
    isAuthenticated = true;
    notifyListeners();
  }

  Future<void> logout() async {
    token = '';
    isAuthenticated = false;
    notifyListeners();
  }
}
