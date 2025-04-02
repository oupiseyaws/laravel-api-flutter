import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

import '../services/api.dart';

class AuthProvider extends ChangeNotifier {
  bool isAuthenticated = false;
  late String token;
  ApiService apiService = ApiService('');
  final storage = FlutterSecureStorage();

  AuthProvider() {
    getToken().then((value) {
      token = value;
      isAuthenticated = true;
      notifyListeners();
        });
  }

  Future<void> register(String name, String email, String password,
      String passwordConfirmation, String deviceName) async {
    token = await apiService.register(
        name, email, password, passwordConfirmation, deviceName);
    setToken(token);
    isAuthenticated = true;
    notifyListeners();
  }

  Future<void> login(String email, String password, String deviceName) async {
    token = await apiService.login(email, password, deviceName);
    setToken(token);
    isAuthenticated = true;
    notifyListeners();
  }

  Future<void> logout() async {
    token = '';
    isAuthenticated = false;
    notifyListeners();
  }

  Future<String> getToken() async {
    try {
      String? token = await storage.read(key: 'token');
      if (token != null) {
        return token ?? '';
      }

      return '';
    } catch (e) {
      return '';
    }
  }

  Future<String> setToken(String token) async {
    await storage.write(key: 'token', value: token);

    return token;
  }
}
