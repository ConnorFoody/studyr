//
//  AppDelegate.m
//  Studyr
//
//  Created by Jon Patsenker on 2/24/15.
//  Copyright (c) 2015 Jonathan Patsenker. All rights reserved.
//

#import "AppDelegate.h"
#import "DetailViewController.h"
#import "User.h"
#import "Group.h"

@interface AppDelegate () <UISplitViewControllerDelegate>

@end

@implementation AppDelegate



- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions {
    // Override point for customization after application launch.
    UISplitViewController *splitViewController = (UISplitViewController *)self.window.rootViewController;
    UINavigationController *navigationController = [splitViewController.viewControllers lastObject];
    navigationController.topViewController.navigationItem.leftBarButtonItem = splitViewController.displayModeButtonItem;
    splitViewController.delegate = self;
    
    /************ TESTS **************/
    // try all the user and group constructors
    @autoreleasepool {
        NSLog(@"starting user alloc tests");

        User* u_basic = [[User alloc] init];
        NSLog(@"basic user: %@\n", u_basic.printUser);
        
        User* u_mid = [[User alloc] initBasic:@"connor"];
        NSLog(@"mid user: %@\n", u_mid.printUser);
        
        
        User* u_full = [[User alloc] initWithAll:@"connor" :[NSArray arrayWithObjects:@"one", @"two", nil] :@"CS" :5];
        NSLog(@"fulll user: %@\n", u_full.printUser);
        
        NSLog(@"done with user alloc tests\n");
        NSLog(@"starting group alloc tests\n");
        
        Group* g_basic = [[Group alloc] init];
        NSLog(@"basic group: %@\n", g_basic.printGroup);
        
        Group* g_mid = [[Group alloc] initFull:@"very study": nil : @"make all the studyz"];
        NSLog(@"mid group: %@\n", g_mid.printGroup);
        
        
        Group* g_full = [[Group alloc] initFull:@"mas estewdy-os" :[NSArray arrayWithObjects:u_basic, u_mid, u_full, u_full, nil]:@"make the etudiars"];
        NSLog(@"full group: %@\n", g_full.printGroup);
        NSLog(@"All done testing group stuff\n");
    }
    
    // try user specific stuff
    @autoreleasepool {
        // try adding and removing classes
        NSLog(@"\n\ntesting add and remove user class\n\n");
        User* u_full = [[User alloc] initWithAll:@"connor" :[NSArray arrayWithObjects:@"one", @"two", nil] :@"CS" :5];
        [u_full removeClass:@"two"];
        [u_full removeClass:@"three"];
        [u_full addClass:@"three"];
        NSLog(@"two classes: %@\n", u_full.printUser);
        
        User* u_other = [[User alloc] initBasic:@"other"];
        [u_other addClass:@"four"];
        [u_other setRating:9];
        
        NSLog(@"Building group\n");
        Group* group = [[Group alloc] init];
        [group addMember:u_other];
        [group addMember:u_other];
        [group addMember:u_full];
        [group removeMember:u_other];
        [group removeMember:u_other];
        NSLog(@"group: %@\n", group.printGroup);
        [group removeMember:u_full];
        NSLog(@"empty group: %@\n", group.printGroup);
        
    }
    
    
    /************ TESTS **************/
    
    return YES;
}

- (void)applicationWillResignActive:(UIApplication *)application {
    // Sent when the application is about to move from active to inactive state. This can occur for certain types of temporary interruptions (such as an incoming phone call or SMS message) or when the user quits the application and it begins the transition to the background state.
    // Use this method to pause ongoing tasks, disable timers, and throttle down OpenGL ES frame rates. Games should use this method to pause the game.
}

- (void)applicationDidEnterBackground:(UIApplication *)application {
    // Use this method to release shared resources, save user data, invalidate timers, and store enough application state information to restore your application to its current state in case it is terminated later.
    // If your application supports background execution, this method is called instead of applicationWillTerminate: when the user quits.
}

- (void)applicationWillEnterForeground:(UIApplication *)application {
    // Called as part of the transition from the background to the inactive state; here you can undo many of the changes made on entering the background.
}

- (void)applicationDidBecomeActive:(UIApplication *)application {
    // Restart any tasks that were paused (or not yet started) while the application was inactive. If the application was previously in the background, optionally refresh the user interface.
}

- (void)applicationWillTerminate:(UIApplication *)application {
    // Called when the application is about to terminate. Save data if appropriate. See also applicationDidEnterBackground:.
}

#pragma mark - Split view

- (BOOL)splitViewController:(UISplitViewController *)splitViewController collapseSecondaryViewController:(UIViewController *)secondaryViewController ontoPrimaryViewController:(UIViewController *)primaryViewController {
    if ([secondaryViewController isKindOfClass:[UINavigationController class]] && [[(UINavigationController *)secondaryViewController topViewController] isKindOfClass:[DetailViewController class]] && ([(DetailViewController *)[(UINavigationController *)secondaryViewController topViewController] detailItem] == nil)) {
        // Return YES to indicate that we have handled the collapse by doing nothing; the secondary controller will be discarded.
        return YES;
    } else {
        return NO;
    }
}

@end
