//
//  User.m
//  Studyr
//
//  Created by connor foody on 3/2/15.
//  Copyright (c) 2015 Jonathan Patsenker. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "User.h"

@implementation User{
    NSString* m_name;
    int m_rating;
    NSMutableArray* m_classes;
    NSString* m_major;
}

// getters
- (NSString*) getName{
    return m_name;
}

- (int) getRating{
    return m_rating;
}

- (NSArray*) getClasses{
    return m_classes;
}

- (NSString*) getMajor{
    return m_major;
}
// setters

- (void) setName:(NSString *)name{
    m_name = name;
}

- (void) setRating:(int)rating{
    m_rating = rating;
}

- (void) addClass:(NSString *)subject{
    // am I checking contains properly? If not, also check removeClass
    if([m_classes containsObject:subject]){
        NSLog(@"WARNING: tried to add a class, %@, that already exists in user %@\n", subject, self.getName);
    }
    else{
        [m_classes addObject:subject];
    }
}

- (void) removeClass:(NSString *)subject{
    if([m_classes containsObject:subject]){
        [m_classes removeObject:subject];
    }
    else{
        NSLog(@"WARNING: tried to remove a class, %@, that does not exist in user %@\n", subject, self.getName);
    }
}

- (void) setMajor:(NSString *)major{
    m_major = major;
}





@end