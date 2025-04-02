import 'package:flutter/material.dart';

import '/providers/auth_provider.dart';
import '../models/category.dart';
import '../services/api.dart';

class CategoryProvider extends ChangeNotifier {
  List<Category> categories = [];
  late ApiService apiService;
  late AuthProvider authProvider;

  CategoryProvider(AuthProvider authProvider) {
    this.authProvider = authProvider;
    apiService = ApiService(authProvider.token);
    init();
  }

  Future init() async {
    try {
      categories = await apiService.fetchCategories();
    } catch (e) {
      print('Failed to load categories: $e');
    }
    notifyListeners();
  }

  Future updateCategory(Category category) async {
    try {
      Category updatedCategory = await apiService.saveCategory(category);
      int index = categories.indexOf(category);
      categories[index] = updatedCategory;

      notifyListeners();
    } catch (e) {
      print('Failed to update category: $e');
    }
  }

  Future addCategory(String name) async {
    try {
      Category addedCategory = await apiService.addCategory(name);
      categories.add(addedCategory);

      notifyListeners();
    } catch (e) {
      print('Failed to create category: $e');
    }
  }

  Future deleteCategory(Category category) async {
    try {
      await apiService.deleteCategory(category.id);
      categories.remove(category);

      notifyListeners();
    } catch (e) {
      print('Failed to delete category: $e');
    }
  }
}
