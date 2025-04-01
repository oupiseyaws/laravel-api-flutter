import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import 'providers/auth_provider.dart';
import 'providers/category_provider.dart';
import 'screens/auth/login.dart';
import 'screens/auth/register.dart';
import 'screens/categories/categories_list.dart';
import 'screens/home.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
        create: (context) => AuthProvider(),
        child: Consumer<AuthProvider>(builder: (context, authProvider, child) {
          return MultiProvider(
              providers: [
                ChangeNotifierProvider<CategoryProvider>(
                    create: (context) => CategoryProvider(authProvider)),
              ],
              child: MaterialApp(title: 'Welcome to Flutter', routes: {
                '/': (context) {
                  final authProvider = Provider.of<AuthProvider>(context);
                  return authProvider.isAuthenticated
                      ? CategoriesList()
                      : Login();
                },
                '/login': (context) => Login(),
                '/register': (context) => Register(),
                '/home': (context) => Home(),
                '/categories': (context) => CategoriesList(),
              }));
        }));
  }
}
