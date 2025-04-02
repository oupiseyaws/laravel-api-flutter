import 'package:flutter/material.dart';
import '../models/transaction.dart';
import '../providers/auth_provider.dart';
import '../services/api.dart';

class TransactionProvider extends ChangeNotifier {
  List<Transaction> transactions = [];
  late ApiService apiService;
  late AuthProvider authProvider;

  TransactionProvider(AuthProvider authProvider) {
    this.authProvider = authProvider;
    init();
  }

  Future<void> init() async {
    String? token = await authProvider.getToken();
    apiService = ApiService(token);
    transactions = await apiService.fetchTransactions();
  
    notifyListeners();
  }

  Future<void> addTransaction(
      String amount, String category, String description, String date) async {
    try {
      Transaction addedTransaction =
          await apiService.addTransaction(amount, category, description, date);
      transactions.add(addedTransaction);
      notifyListeners();
    } catch (e) {
      print(e);
    }
  }

  Future<void> updateTransaction(Transaction transaction) async {
    try {
      Transaction updatedTransaction =
          await apiService.updateTransaction(transaction);
      int index = transactions.indexOf(transaction);
      transactions[index] = updatedTransaction;
      notifyListeners();
    } catch (e) {
      print(e);
    }
  }

  Future<void> deleteTransaction(Transaction transaction) async {
    try {
      await apiService.deleteTransaction(transaction.id);
      transactions.remove(transaction);
      notifyListeners();
    } catch (e) {
      print(e);
    }
  }
}
