<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Port;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ============ DASHBOARD ADMIN ============
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalPorts = Port::count();
        $totalArticles = Article::count();
        return view('admin.dashboard', compact('totalUsers', 'totalPorts', 'totalArticles'));
    }
    
    // ============ CRUD USER ============
    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }
    
    public function usersCreate()
    {
        return view('admin.users-create');
    }
    
    public function usersStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        return redirect('/admin/users')->with('success', 'User created successfully!');
    }
    
    public function usersEdit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users-edit', compact('user'));
    }
    
    public function usersUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        
        return redirect('/admin/users')->with('success', 'User updated successfully!');
    }
    
    public function usersDelete($id)
    {
        $user = User::findOrFail($id);
        if ($user->email === 'admin@example.com') {
            return redirect('/admin/users')->with('error', 'Cannot delete main admin!');
        }
        $user->delete();
        return redirect('/admin/users')->with('success', 'User deleted successfully!');
    }
    
    // ============ CRUD PORT ============
    public function ports()
    {
        $ports = Port::all();
        return view('admin.ports', compact('ports'));
    }
    
    public function portsCreate()
    {
        return view('admin.ports-create');
    }
    
    public function portsStore(Request $request)
    {
        $request->validate([
            'port_name' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
        
        Port::create($request->all());
        return redirect('/admin/ports')->with('success', 'Port created successfully!');
    }
    
    public function portsEdit($id)
    {
        $port = Port::findOrFail($id);
        return view('admin.ports-edit', compact('port'));
    }
    
    public function portsUpdate(Request $request, $id)
    {
        $port = Port::findOrFail($id);
        $port->update($request->all());
        return redirect('/admin/ports')->with('success', 'Port updated successfully!');
    }
    
    public function portsDelete($id)
    {
        Port::findOrFail($id)->delete();
        return redirect('/admin/ports')->with('success', 'Port deleted successfully!');
    }
    
    // ============ CRUD ARTICLE ============
    public function articles()
    {
        $articles = Article::all();
        return view('admin.articles', compact('articles'));
    }
    
    public function articlesCreate()
    {
        return view('admin.articles-create');
    }
    
    public function articlesStore(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'author' => 'required',
        ]);
        
        Article::create($request->all());
        return redirect('/admin/articles')->with('success', 'Article created successfully!');
    }
    
    public function articlesEdit($id)
    {
        $article = Article::findOrFail($id);
        return view('admin.articles-edit', compact('article'));
    }
    
    public function articlesUpdate(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        $article->update($request->all());
        return redirect('/admin/articles')->with('success', 'Article updated successfully!');
    }
    
    public function articlesDelete($id)
    {
        Article::findOrFail($id)->delete();
        return redirect('/admin/articles')->with('success', 'Article deleted successfully!');
    }
}