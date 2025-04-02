import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import './providers/auth_provider.dart';
import './providers/category_provider.dart';
import './providers/transaction_provider.dart';
import './screens/auth/login.dart';
import './screens/auth/register.dart';
import './screens/categories/categories_list.dart';

import './screens/home.dart';

void main() {
  runApp(const MyApp());
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
                ChangeNotifierProvider<TransactionProvider>(
                    create: (context) => TransactionProvider(authProvider)),
              ],
              child: MaterialApp(title: 'Welcome to Flutter', routes: {
                '/': (context) {
                  final authProvider = Provider.of<AuthProvider>(context);
                  return authProvider.isAuthenticated
                      ? const Home()
                      : const Login();
                },
                '/login': (context) => const Login(),
                '/register': (context) => const Register(),
                '/home': (context) => const Home(),
                '/categories': (context) => const CategoriesList(),
              }));
        }));
  }
}
